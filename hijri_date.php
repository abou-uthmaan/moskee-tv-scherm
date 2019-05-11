<?php
$url = 'https://timesprayer.com/hijri-date-in-saudi-arabia.html';
$content = file_get_contents($url);
$first_step = explode( '<td>تاريخ اليوم هجري</td><td>' , $content );
$second_step = explode("</td>" , $first_step[1] );

$date=explode(' ',$second_step[0]);

$number_of_day = date('w');
$day_array = array('الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت');
$day_array[$number_of_day];

echo "<span>".$date[2]."</span> "."<bdo dir='rtl'><span class='ar'>".$date[1]."</span></bdo> "."<span>".$date[0]."</span> <span class='ar'>".$day_array[$number_of_day]."</span>";
?>