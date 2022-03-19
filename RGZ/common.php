<?php
function get_period($begin, $end) {
    if ($begin && $end)
        return "за период от '$begin' до '$end'";
    elseif ($begin)
        return "начиная с '$begin'";
    elseif ($end)
        return "заканчивая '$end'";
    else
        return "за всё время";
}
