<?php
/*
Plugin Name: Balsamico News
Description: Adds a widget for displaying the latest news from http://www.aceto-balsamico.net/
Version: 1.14
Author: MS
Author URI: http://www.aceto-balsamico.net/
License: GPL3
*/

<?php

function scbalsamico()
{
  $config = get_option("widget_scbalsamico");
  if (!is_array($config)){
    $config = array(
      'title' => 'Balsamico News',
      'news' => '5',
      'chars' => '30'
    );
  }

  // create rss
  $rss = simplexml_load_file( 
  'http://www.aceto-balsamico.net/feed/'); 
  ?> 
  
  <ul> 
  
  <?php 
  // maximum show posts
  $max_news = $config['news'];
  // maximum text length
  $max_length = $config['chars'];
  
  // procedure
  $cnt = 0;
  foreach($rss->channel->item as $i) { 
    if($max_news > 0 AND $cnt >= $max_news){
        break;
    }
    ?> 
    
    <li>
    <?php
    $title = $i->title;
    // get length
    $length = strlen($title);
	//strip
    if($length > $max_length){
      $title = substr($title, 0, $max_length)."...";
    }
    ?>
    <a href="<?=$i->link?>"><?=$title?></a> 
    </li> 
    
    <?php 
    $cnt++;
  } 
  ?> 
  
  </ul>
<?php  
}

function widget_scbalsamico($args)
{
  extract($args);
  
  $config = get_option("widget_scbalsamico");
  if (!is_array($config)){
    $config = array(
      'title' => 'touchscreen-handy.de News',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  echo $before_widget;
  echo $before_title;
  echo $config['title'];
  echo $after_title;
  scbalsamico();
  echo $after_widget;
}

function scbalsamico_control()
{
  $config = get_option("widget_scbalsamico");
  if (!is_array($config)){
    $config = array(
      'title' => 'Balsamico News',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  if($_POST['scbalsamico-Submit'])
  {
    $config['title'] = htmlspecialchars($_POST['scbalsamico-WidgetTitle']);
    $config['news'] = htmlspecialchars($_POST['scbalsamico-NewsCount']);
    $config['chars'] = htmlspecialchars($_POST['scbalsamico-CharCount']);
    update_option("widget_scbalsamico", $config);
  }
?> 
  <p>
    <label for="scbalsamico-WidgetTitle">Name of Widget: </label>
    <input type="text" id="scbalsamico-WidgetTitle" name="scbalsamico-WidgetTitle" value="<?php echo $config['title'];?>" />
    <br /><br />
    <label for="scbalsamico-NewsCount">Maximum News shown: </label>
    <input type="text" id="scbalsamico-NewsCount" name="scbalsamico-NewsCount" value="<?php echo $config['news'];?>" />
    <br /><br />
    <label for="scbalsamico-CharCount">Maximum Characters shown: </label>
    <input type="text" id="scbalsamico-CharCount" name="scbalsamico-CharCount" value="<?php echo $config['chars'];?>" />
    <br /><br />
    <input type="hidden" id="scbalsamico-Submit"  name="scbalsamico-Submit" value="1" />
  </p>
  
<?php
}

function scbalsamico_init()
{
  register_sidebar_widget(__('Balsamico News'), 'widget_scbalsamico');    
  register_widget_control('Balsamico News', 'scbalsamico_control', 300, 200);
}
add_action("plugins_loaded", "scbalsamico_init");
?>
