@php
    function round_out($value)
    {
        $decimal = 0;

        if(strlen(stristr($value, '.0000')) == 0){
            $decimal = 4;
        }

        return number_format($value, $decimal);
    }
@endphp
