<?xml version="1.0"?>
<xsl:stylesheet version='1.0'
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
  xmlns:c="http://example/countries#">

  <xsl:template match="countries">
    <rdf:RDF>
      <xsl:apply-templates/>
    </rdf:RDF>
  </xsl:template>

  <xsl:template match="country">
    <c:country rdf:about="http://example/countries/object/countries#{translate(@name, ' ', '_')}">
      <c:name><xsl:value-of select="@name"/></c:name>
      <c:population rdf:datatype="http://www.w3.org/2001/XMLSchema#int"><xsl:value-of select="@population"/></c:population>
      <c:area><xsl:value-of select="@area"/></c:area>
      <c:wikipedia rdf:resource="https://en.wikipedia.org/wiki/{translate(@name, ' ', '_')}"/>
    </c:country>
    <xsl:apply-templates select="city"/>
    <!--xsl:apply-templates select="language"/-->
  </xsl:template>

  <xsl:template match="city">
    <c:city rdf:about="http://example/countries/object/cities#{translate(name, ' ', '_')}">
      <c:name><xsl:value-of select="name"/></c:name>
      <c:population rdf:datatype="http://www.w3.org/2001/XMLSchema#int"><xsl:value-of select="population"/></c:population>
      <c:country rdf:resource="http://example/countries/object/countries#{translate(ancestor::country/@name, ' ', '_')}"/>
    </c:city>
  </xsl:template>

  <xsl:template match="language">
    <c:lang rdf:about="http://example/countries/object/languages#{translate(., ' ', '_')}">
      <c:name><xsl:value-of select="."/></c:name>
      <c:Bag>
        <c:country rdf:resource="http://example/countries/object/countries#{translate(ancestor::country/@name, ' ', '_')}"/>
        <c:percent><xsl:value-of select="@percentage"/></c:percent>
      </c:Bag>
    </c:lang>
  </xsl:template>

</xsl:stylesheet>
