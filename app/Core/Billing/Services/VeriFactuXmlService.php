<?php

namespace App\Core\Billing\Services;

use App\Core\Billing\Models\Invoice;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class VeriFactuXmlService
{
    /**
     * Generate XML for VeriFactu (AEAT)
     */
    public function generateXml(Invoice $invoice): string
    {
        $invoice->load(['items', 'professional.user', 'contact', 'appointment']);
        
        $professional = $invoice->professional;
        $user = $professional->user;
        $contact = $invoice->contact;

        // Generate XML according to VeriFactu format
        $xml = new \DOMDocument('1.0', 'UTF-8');
        $xml->formatOutput = true;

        // Root element: Facturae (Spanish e-invoice standard)
        $root = $xml->createElement('Facturae');
        $root->setAttribute('xmlns', 'http://www.facturae.gob.es/formato/Versiones/Facturaev3_2_2.xml');
        $root->setAttribute('xmlns:ds', 'http://www.w3.org/2000/09/xmldsig#');
        $xml->appendChild($root);

        // FileHeader
        $fileHeader = $xml->createElement('FileHeader');
        $schemaVersion = $xml->createElement('SchemaVersion', '3.2.2');
        $modality = $xml->createElement('Modality', 'I'); // I = Invoice
        $invoiceNumber = $xml->createElement('InvoiceNumber', $invoice->invoice_number);
        $fileHeader->appendChild($schemaVersion);
        $fileHeader->appendChild($modality);
        $fileHeader->appendChild($invoiceNumber);
        $root->appendChild($fileHeader);

        // Parties
        $parties = $xml->createElement('Parties');
        
        // Seller (Professional)
        $seller = $this->createPartyElement($xml, $user, $professional, true);
        $parties->appendChild($seller);
        
        // Buyer (Contact)
        $buyer = $this->createPartyElement($xml, $contact, null, false);
        $parties->appendChild($buyer);
        
        $root->appendChild($parties);

        // Invoice
        $invoiceElement = $xml->createElement('Invoice');
        
        // InvoiceIssueData
        $issueData = $xml->createElement('InvoiceIssueData');
        $issueDate = $xml->createElement('IssueDate', $invoice->issue_date->format('Y-m-d'));
        $issueTime = $xml->createElement('IssueTime', $invoice->issue_date->format('H:i:s'));
        $invoiceCurrencyCode = $xml->createElement('InvoiceCurrencyCode', $invoice->currency);
        $issueData->appendChild($issueDate);
        $issueData->appendChild($issueTime);
        $issueData->appendChild($invoiceCurrencyCode);
        $invoiceElement->appendChild($issueData);

        // TaxOutputs
        $taxOutputs = $xml->createElement('TaxOutputs');
        
        // For psychologists, IVA is exempt
        $tax = $xml->createElement('Tax');
        $taxTypeCode = $xml->createElement('TaxTypeCode', '01'); // 01 = IVA
        $taxRate = $xml->createElement('TaxRate', '0.00');
        $taxableBase = $xml->createElement('TaxableBase', number_format($invoice->subtotal, 2, '.', ''));
        $taxAmount = $xml->createElement('TaxAmount', '0.00');
        $tax->appendChild($taxTypeCode);
        $tax->appendChild($taxRate);
        $tax->appendChild($taxableBase);
        $tax->appendChild($taxAmount);
        $taxOutputs->appendChild($tax);
        
        $invoiceElement->appendChild($taxOutputs);

        // InvoiceTotals
        $totals = $xml->createElement('InvoiceTotals');
        $totalGrossAmount = $xml->createElement('TotalGrossAmount', number_format($invoice->subtotal, 2, '.', ''));
        $totalTaxOutputs = $xml->createElement('TotalTaxOutputs', '0.00');
        $totalTaxesWithheld = $xml->createElement('TotalTaxesWithheld', number_format($invoice->irpf_retention ?? 0, 2, '.', ''));
        $invoiceTotal = $xml->createElement('InvoiceTotal', number_format($invoice->total, 2, '.', ''));
        $totals->appendChild($totalGrossAmount);
        $totals->appendChild($totalTaxOutputs);
        $totals->appendChild($totalTaxesWithheld);
        $totals->appendChild($invoiceTotal);
        $invoiceElement->appendChild($totals);

        // Items
        $items = $xml->createElement('Items');
        foreach ($invoice->items as $index => $item) {
            $itemElement = $xml->createElement('InvoiceLine');
            
            $itemNumber = $xml->createElement('ItemNumber', $index + 1);
            $itemDescription = $xml->createElement('ItemDescription', htmlspecialchars($item->description, ENT_XML1, 'UTF-8'));
            $quantity = $xml->createElement('Quantity', number_format($item->quantity, 2, '.', ''));
            $unitOfMeasure = $xml->createElement('UnitOfMeasure', 'C62'); // C62 = Unit
            $unitPriceWithoutTax = $xml->createElement('UnitPriceWithoutTax', number_format($item->unit_price, 2, '.', ''));
            $totalCost = $xml->createElement('TotalCost', number_format($item->subtotal, 2, '.', ''));
            
            $itemElement->appendChild($itemNumber);
            $itemElement->appendChild($itemDescription);
            $itemElement->appendChild($quantity);
            $itemElement->appendChild($unitOfMeasure);
            $itemElement->appendChild($unitPriceWithoutTax);
            $itemElement->appendChild($totalCost);
            
            $items->appendChild($itemElement);
        }
        $invoiceElement->appendChild($items);

        // LegalLiterals (for IVA exemption)
        if ($invoice->is_iva_exempt) {
            $legalLiterals = $xml->createElement('LegalLiterals');
            $legalReference = $xml->createElement('LegalReference', 'Ley 37/1992, Art. 20.1 - Exento de IVA');
            $legalLiterals->appendChild($legalReference);
            $invoiceElement->appendChild($legalLiterals);
        }

        $root->appendChild($invoiceElement);

        // Save XML
        $filename = $this->getXmlFilename($invoice);
        $path = config('billing.invoice.storage_path', 'invoices') . '/' . $filename;
        
        $disk = Storage::disk(config('billing.invoice.storage_disk', 'local'));
        $disk->put($path, $xml->saveXML());

        return $path;
    }

    /**
     * Create party element (seller or buyer)
     */
    private function createPartyElement(\DOMDocument $xml, $entity, $professional = null, bool $isSeller = true): \DOMElement
    {
        $party = $xml->createElement($isSeller ? 'SellerParty' : 'BuyerParty');
        
        // TaxIdentification
        $taxIdentification = $xml->createElement('TaxIdentification');
        $personTypeCode = $xml->createElement('PersonTypeCode', $isSeller ? 'J' : 'F'); // J = Legal, F = Individual
        $residenceTypeCode = $xml->createElement('ResidenceTypeCode', 'R'); // R = Resident
        $taxIdentificationNumber = $xml->createElement('TaxIdentificationNumber', $this->getTaxId($entity, $professional));
        $taxIdentification->appendChild($personTypeCode);
        $taxIdentification->appendChild($residenceTypeCode);
        $taxIdentification->appendChild($taxIdentificationNumber);
        $party->appendChild($taxIdentification);

        // AdministrativeCentre (for seller)
        if ($isSeller && $professional) {
            $adminCentre = $xml->createElement('AdministrativeCentre');
            $centreCode = $xml->createElement('CentreCode', '1');
            $centreName = $xml->createElement('CentreName', htmlspecialchars($entity->full_name ?? '', ENT_XML1, 'UTF-8'));
            $adminCentre->appendChild($centreCode);
            $adminCentre->appendChild($centreName);
            $party->appendChild($adminCentre);
        }

        // Individual or Legal Entity
        if ($isSeller) {
            $individual = $xml->createElement('Individual');
            $name = $xml->createElement('Name', htmlspecialchars($entity->first_name ?? '', ENT_XML1, 'UTF-8'));
            $firstSurname = $xml->createElement('FirstSurname', htmlspecialchars($entity->last_name ?? '', ENT_XML1, 'UTF-8'));
            $individual->appendChild($name);
            $individual->appendChild($firstSurname);
            $party->appendChild($individual);
        } else {
            $legalEntity = $xml->createElement('LegalEntity');
            $corporateName = $xml->createElement('CorporateName', htmlspecialchars($entity->full_name ?? $entity->first_name . ' ' . $entity->last_name, ENT_XML1, 'UTF-8'));
            $legalEntity->appendChild($corporateName);
            $party->appendChild($legalEntity);
        }

        // Address
        $address = $xml->createElement('AddressInSpain');
        if ($isSeller && $professional) {
            $street = $xml->createElement('Address', htmlspecialchars($professional->address_street ?? '', ENT_XML1, 'UTF-8'));
            $postCode = $xml->createElement('PostCode', htmlspecialchars($professional->address_postal_code ?? '', ENT_XML1, 'UTF-8'));
            $town = $xml->createElement('Town', htmlspecialchars($professional->address_city ?? '', ENT_XML1, 'UTF-8'));
            $province = $xml->createElement('Province', htmlspecialchars($professional->address_city ?? '', ENT_XML1, 'UTF-8'));
        } else {
            // Contact entity
            $street = $xml->createElement('Address', htmlspecialchars($entity->address_street ?? '', ENT_XML1, 'UTF-8'));
            $postCode = $xml->createElement('PostCode', htmlspecialchars($entity->address_postal_code ?? '', ENT_XML1, 'UTF-8'));
            $town = $xml->createElement('Town', htmlspecialchars($entity->address_city ?? '', ENT_XML1, 'UTF-8'));
            $province = $xml->createElement('Province', htmlspecialchars($entity->address_city ?? '', ENT_XML1, 'UTF-8'));
        }
        $address->appendChild($street);
        $address->appendChild($postCode);
        $address->appendChild($town);
        $address->appendChild($province);
        $party->appendChild($address);

        return $party;
    }

    /**
     * Get tax identification number
     */
    private function getTaxId($entity, $professional = null): string
    {
        if ($professional && $entity->dni) {
            return strtoupper(trim($entity->dni));
        }
        
        // Fallback to a default format if no DNI
        return '00000000A';
    }

    /**
     * Get XML filename
     */
    private function getXmlFilename(Invoice $invoice): string
    {
        return sprintf(
            'factura_%s_%s.xml',
            $invoice->invoice_number,
            $invoice->issue_date->format('Y-m-d')
        );
    }
}
