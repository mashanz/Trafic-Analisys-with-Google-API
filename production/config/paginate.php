<?php
/*--------------------------------------------------------------------------------------------
|    @desc:         pagination 
|    @author:       Aravind Buddha
|    @url:          http://www.techumber.com
|    @date:         12 August 2012
|    @email         aravind@techumber.com
|    @license:      Free!, to Share,copy, distribute and transmit , 
|                   but i'll be glad if i my name listed in the credits'
---------------------------------------------------------------------------------------------*/
function paginate($reload, $page, $tpages) {
    $adjacents = 5;
    $prevlabel = "&lsaquo; Prev";
    $nextlabel = "Next &rsaquo;";
    $out = "";
    // previous
    if ($page == 1) {
        $out.= "<span>" . $prevlabel . "</span>\n";
    } elseif ($page == 2) {
        $out.= "<a  href=\"" . $reload . "\">" . $prevlabel . "</a>\n";
    } else {
        $out.= "<a  href=\"" . $reload . "&amp;page=" . ($page - 1) . "\">" . $prevlabel . "</a>\n";
    }
  
    $pmin = ($page > $adjacents) ? ($page - $adjacents) : 1;
    $pmax = ($page < ($tpages - $adjacents)) ? ($page + $adjacents) : $tpages;
    for ($i = $pmin; $i <= $pmax; $i++) {
        if ($i == $page) {
            $out.= "" . $i . "</a> | ";
        } elseif ($i == 1) {
            $out.= "<a  href=\"" . $reload . "\">" . $i . "</a> | ";
        } else {
            $out.= "<a  href=\"" . $reload . "&amp;page=" . $i . "\">" . $i . "</a> | ";
        }
    }
    
    if ($page < ($tpages - $adjacents)) {
        $out.= "<a href=\"" . $reload . "&amp;page=" . $tpages . "\">" . $tpages . "</a> | ";
    }
    // next
    if ($page < $tpages) {
        $out.= "<a  href=\"" . $reload . "&amp;page=" . ($page + 1) . "\">" . $nextlabel . "</a>";
    } else {
        $out.= "<span>" . $nextlabel . "</span>";
    }
    $out.= "";
    return $out;
}
