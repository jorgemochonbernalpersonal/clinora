<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="2.0"
                xmlns:html="http://www.w3.org/1999/xhtml"
                xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
                xmlns:sitemap="http://www.sitemaps.org/schemas/sitemap/0.9"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>
    <xsl:template match="/">
        <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <title>XML Sitemap - Clinora</title>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                <style type="text/css">
                    body {
                        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
                        color: #333;
                        max-width: 1200px;
                        margin: 0 auto;
                        padding: 20px;
                        background-color: #f5f5f5;
                    }
                    h1 {
                        color: #2563eb;
                        border-bottom: 3px solid #2563eb;
                        padding-bottom: 10px;
                    }
                    .intro {
                        background-color: #fff;
                        padding: 20px;
                        border-radius: 8px;
                        margin-bottom: 20px;
                        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                    }
                    .intro a {
                        color: #2563eb;
                        text-decoration: none;
                    }
                    .intro a:hover {
                        text-decoration: underline;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        background-color: #fff;
                        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                        border-radius: 8px;
                        overflow: hidden;
                    }
                    th {
                        background-color: #2563eb;
                        color: #fff;
                        text-align: left;
                        padding: 12px;
                        font-weight: 600;
                    }
                    td {
                        padding: 12px;
                        border-bottom: 1px solid #e5e7eb;
                    }
                    tr:hover {
                        background-color: #f9fafb;
                    }
                    .url {
                        color: #2563eb;
                        text-decoration: none;
                        word-break: break-all;
                    }
                    .url:hover {
                        text-decoration: underline;
                    }
                    .priority {
                        font-weight: 600;
                        color: #059669;
                    }
                    .changefreq {
                        text-transform: capitalize;
                        color: #7c3aed;
                    }
                    .lastmod {
                        color: #6b7280;
                        font-size: 0.9em;
                    }
                    .footer {
                        margin-top: 30px;
                        text-align: center;
                        color: #6b7280;
                        font-size: 0.9em;
                    }
                </style>
            </head>
            <body>
                <h1>XML Sitemap</h1>
                <div class="intro">
                    <p>
                        Este es un sitemap XML generado automáticamente por <strong>Clinora</strong>.
                        Para más información sobre sitemaps, visita 
                        <a href="https://www.sitemaps.org/" target="_blank">sitemaps.org</a>.
                    </p>
                    <p>
                        Este sitemap contiene <strong><xsl:value-of select="count(sitemap:urlset/sitemap:url)"/></strong> URL(s).
                    </p>
                    <p style="margin-top: 10px; padding: 10px; background-color: #f0f9ff; border-left: 4px solid #2563eb; border-radius: 4px;">
                        <strong>💡 Nota:</strong> Las fechas de última modificación se basan en las fechas reales de modificación de los archivos.
                    </p>
                </div>
                <table>
                    <tr>
                        <th>URL</th>
                        <th>Prioridad</th>
                        <th>Frecuencia de Cambio</th>
                        <th>Última Modificación</th>
                    </tr>
                    <xsl:for-each select="sitemap:urlset/sitemap:url">
                        <tr>
                            <td>
                                <xsl:variable name="itemURL">
                                    <xsl:value-of select="sitemap:loc"/>
                                </xsl:variable>
                                <a href="{$itemURL}" class="url">
                                    <xsl:value-of select="sitemap:loc"/>
                                </a>
                            </td>
                            <td>
                                <span class="priority">
                                    <xsl:value-of select="sitemap:priority"/>
                                </span>
                            </td>
                            <td>
                                <span class="changefreq">
                                    <xsl:value-of select="sitemap:changefreq"/>
                                </span>
                            </td>
                            <td>
                                <span class="lastmod">
                                    <xsl:value-of select="sitemap:lastmod"/>
                                </span>
                            </td>
                        </tr>
                    </xsl:for-each>
                </table>
                <div class="footer">
                    <p>Generado por Clinora</p>
                </div>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>

