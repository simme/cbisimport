<?php
// $Id$

$attr = $product['Attributes'];

$arenas = $product['Occasions'];
foreach($arenas as $arena) {
  $arena_name = $arena->ArenaName;
  if($arena_name) {
    $attr['arena'] = $arena_name;
    break;
  }
}

$links = array();
if (!empty($attr['email'])) {
  $links['email'] = array(
    'href' => 'mailto:' . $attr['email'],
    'title' => $attr['email'],
  );
}

if (!empty($attr['website'])) {
  $attr['website'] = check_plain($attr['website']);
  if (!preg_match('/^https?\:\/\//', $attr['website'])) {
    $href = 'http://' . $attr['website'];
  }
  else {
    $href = $attr['website'];
  }
  $links['website'] = array(
    'href' => $href,
    'title' => $attr['website'],
    'attributes' => array('rel' => 'home'),
  );
}

print $attr['Description'];
print theme('links', $links);
print theme('cbis_product_address', $attr);