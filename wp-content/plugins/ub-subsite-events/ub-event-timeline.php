<?php
/**
 * Created by PhpStorm.
 * User: tvenna
 * Date: 8/15/17
 * Time: 1:30 PM
 */

add_shortcode('ub-events-timeline', 'ub_events_timeline_show');

function ub_events_timeline_show($attrs){

	$html ='';

	ub_events_enqueue_scripts();

	$category = $attrs['category'];
	$events= ub_events_get_events($category);
	$html .= ub_events_get_timeline($events);

	return $html;
}


function ub_events_enqueue_scripts(){

	wp_enqueue_style('ub-events-timeline', plugin_dir_url(__FILE__).'ub-subsite-events.css');
}

/*returns the post objects of the events in the category*/

function ub_events_get_events($category){




	$args= array(
		'posts_per_page' => -1,
		'post_type' => 'ubevent',
		'meta_key' => '_ubevents_date',
		'orderby' => '_ubevents_date',
		'order' => 'ASC',
		'tax_query' => array(
			array(
				'taxonomy' => 'ubevent-categories',
				'field' => 'name',
				'terms' => array($category),
			)
		)
	);


	$events = get_posts($args);

	//var_dump($events);

	$dates = array();

	foreach ($events as $event){
		array_push($dates,get_post_meta($event->ID,'_ubevents_date',true) ) ;
	}

	$dates = array_unique($dates);



	$total_sorted_events=array();


	foreach($dates as $eventdate){

		$day_events = array();

		foreach ($events as $event){


			$current_date= get_post_meta($event->ID,'_ubevents_date',true);

			if($current_date == $eventdate){

				$day_events[$event->ID]=get_post_meta($event->ID,'_ubevents_time',true);

			}

		}

		asort($day_events);

		array_push($total_sorted_events,array_keys($day_events));

	}


	return $total_sorted_events;

}

function ub_events_get_timeline($events){

	$all_events=array();

	foreach ($events as $day_events){

		foreach ($day_events as $day_event){

			array_push($all_events,(string)$day_event);
		}
	}


	

	$content='<ul class="timeline">';


    $count =0;

	foreach ($all_events as $event) {



		$count++;

		if ($count % 2 == 0) {

			$content .= '<li class="timeline-inverted">';

		} else {
			$content .= '<li>';
		}


        $post=get_post($event);
		$event_date = get_post_meta($post->ID, '_ubevents_date', true);

		$content .= '<div class="timeline-badge"><p class="event-month">' . date('M', strtotime($event_date)) . ' </p><p class="event-day"> ' . substr($event_date, 8, 2) . '</p></div>
			<div class="timeline-panel">
				<div class="timeline-heading">
					<h4 class="timeline-title">' . $post->post_title . '</h4>
					
					<p><small class="text-muted">' . ub_events_get_event_time($post->ID) . '</small></p>
					<p><small class="text-muted">' . get_post_meta($post->ID, '_ubevents_location', true) . '</small></p>
				</div>
				<div class="timeline-body">
					<p>' . $post->post_content . '</p>
				</div>
			</div>
		</li>';


	}

	$content .='</ul>';

	?>


	<?php

	return $content;

}

function ub_events_get_event_time($post_id){


	$content='';
    $start= get_post_meta($post_id,'_ubevents_time',true);
    $end= get_post_meta($post_id, '_ubevents_time_end',true);

    $content .= date('g:i a',strtotime($start)).' to '. date('g:i a',strtotime($end));


	return $content;
}


function ub_events_get_day_events($eventdate, $category){

	$args= array(
		'posts_per_page' => -1,
		'post_type' => 'ubevent',
		'meta_value' => $eventdate,
		'meta_key' => '_ubevents_time',
		'orderby' => '_ubevents_time',
		'order' => 'ASC',
		'tax_query' => array(
			array(
				'taxonomy' => 'ubevent-categories',
				'field' => 'name',
				'terms' => array($category),
			)
		)
	);


	$events = get_posts($args);


	foreach ($events as $event){

		echo get_the_title($event->ID).' , '. get_post_meta($event->ID,'_ubevents_time',true).' , '. get_post_meta($event->ID,'_ubevents_date',true);
		echo '<br>';
	}

}

?>