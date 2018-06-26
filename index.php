<?php
set_include_path(get_include_path() . PATH_SEPARATOR . 'easyrdf-0.9.0/lib/');
require_once "EasyRdf.php";
set_include_path(get_include_path() . PATH_SEPARATOR . 'easyrdf-0.9.0/examples/');
require_once "html_tag_helpers.php";

EasyRdf_Namespace::set('c', 'http://example/countries#');

ini_set('display_errors', 'On');
error_reporting(E_ALL);

/*$output = chdir('apache-jena-fuseki-3.6.0');
$output = exec('./fuseki-server --file ../countries.rdf /countries');*/

$sparql = new EasyRdf_Sparql_Client(
    'http://localhost:3030/countries'
);

if (get_magic_quotes_gpc() and isset($_REQUEST['query'])) {
    $_REQUEST['query'] = stripslashes($_REQUEST['query']);
}
?>
<html>
<head>
<title>EasyRdf SPARQL Query Form</title>
<style type="text/css">
.error {
  width: 35em;
  border: 2px red solid;
  padding: 1em;
  margin: 0.5em;
  background-color: #E6E6E6;
}
</style>
</head>
<body>
<h1>Formulaire de requête SPARQL sur le graphe du fichier countries.rdf</h1>

<div style="margin: 0.5em">
<?php
print form_tag();
print "<code>";
print 'PREFIX c: &lt;'.htmlspecialchars("http://example/countries#").'&gt;<br />';
print "</code>";
print text_area_tag('query', "SELECT *\n
WHERE {
  ?x c:country [] .
  ?x c:name ?name .
  ?x c:population ?population
  FILTER (?population < 2000000 && ?population > 1000000)
}
ORDER BY ?population", array('rows' => 10, 'cols' => 80)).'<br />';
print check_box_tag('text') . label_tag('text', 'Plain text results').'<br />';
print submit_tag();
print form_end_tag();
?>
</div>

<?php
if (isset($_REQUEST['query'])) {
  $sparql = new EasyRdf_Sparql_Client(
      'http://localhost:3030/countries'
  );
  try {
      $results = $sparql->query($_REQUEST['query']);
      if (isset($_REQUEST['text'])) {
          print "<pre>".htmlspecialchars($results->dump('text'))."</pre>";
      } else {
          print $results->dump('html');
      }
  } catch (Exception $e) {
      print "<div class='error'>".$e->getMessage()."</div>\n";
  }
}
?>

</body>
</html>
