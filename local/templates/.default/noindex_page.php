<?
$arrIndex = array(
  'asdf',
  '4214',
);
if(strpos($_SERVER['REQUEST_URI'],'filter') !== false && !in_array($_SERVER['REQUEST_URI'],$arrIndex))
  echo '<meta name="robots" content="noindex,follow"/>';
?>
