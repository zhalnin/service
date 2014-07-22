<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 20/07/14
 * Time: 21:46
 */

namespace dmn\classes;
error_reporting( E_ALL & ~E_NOTICE );


/**
 * Describe DateTime"
 */
class FieldDatetime extends Field {

    // Time in format UNIXSTAMP
    protected $time;
    // Minimum allowable year
    protected $begin_year;
    // Maximum allowable year
    protected $end_year;

    public function __construct( $name,
                                $caption,
                                $time,
                                $begin_year = 2000,
                                $end_year = 2020,
                                $parameters = "",
                                $help = "",
                                $help_url = "" )
    {
        parent::__construct( $name,
                                "datetime",
                                $caption,
                                false,
                                $value,
                                $parameters,
                                $help,
                                $help_url );

        if( empty( $time ) ) $this->time = time();
        else if( is_array( $time ) ) {
            $this->time = mktime( $time['hour'],
                $time['minute'],
                0,
                $time['month'],
                $time['day'],
                $time['year'] );
        }
        else $this->time = $time;
        $this->begin_year = $begin_year;
        $this->end_year = $end_year;
    }

    /**
     * Date in format of MySQL
     * @return date
     */
    public function getMysqlFormat() {
        return date( "Y-m-d H:i:s", $this->time );
    }

    /**
     * Function for name of field
     * and tag of control element
     * @return array
     */
    public function getHtml() {
        // Check if not empty style
        if( !empty( $this->css_style ) ) {
            $style = "style=\"".$this->css_style."\"";
        }
        else $style = "";
        // Check if not empty class
        if( !empty( $this->css_class) ) {
            $class = "class=\"".$this->css_class."\"";
        }
        else $class = "";

        // From tag
        $date_month   = @date( "m", $this->time );
        $date_day     = @date( "d", $this->time );
        $date_year    = @date( "Y", $this->time );
        $date_hour    = @date( "H", $this->time );
        $date_minute  = @date( "i", $this->time );

        // Drop-down list for day
        $tag = "<select title=\"Число\"
            $style $class type=\"text\"
            name='".$this->name."[day]'>\n";
        for( $i = 1; $i <= 31; $i++ ) {
            if( $date_day == $i ) $temp = "selected";
            else $temp = "";
            $tag .= "<option value=\"$i\" $temp>".sprintf( "%02d", $i );
        }
        $tag .= "</select>";
//        echo "<tt><pre>".print_r($tag, true)."</pre></tt>";
        // Drop-down list for month
        $tag .= "<select title=\"Месяц\"
            $style $class type=\"text\"
            name='".$this->name."[month]'>\n";
        for( $i = 1; $i <= 12; $i++ ) {
            if( $date_month == $i ) $temp = "selected";
            else $temp = "";
            $tag .= "<option value=\"$i\" $temp>".sprintf( "%02d", $i );
        }
        $tag .= "</select>";

        // Drop-down list for year
        $tag .= "<select title=\"Год\"
            $style $class type=\"text\"
            name='".$this->name."[year]'>\n";
        for( $i = 2004; $i <= 2017; $i++ ) {
            if( $date_year == $i ) $temp = "selected";
            else $temp = "";
            $tag .= "<option value=\"$i\" $temp>$i";
        }
        $tag .= "</select>";

        // Drop-down list for hour
        $tag .= "&nbsp;&nbsp;<select
            title=\"Часы\" $style $class
            type=\"text\" name='".$this->name."[hour]'>";
        for( $i = 0; $i <= 23; $i++ ) {
            if( $date_hour == $i ) $temp = "selected";
            else $temp = "";
            $tag .= "<option value=\"$i\" $temp>".sprintf( "%02d", $i );
        }
        $tag .= "</select>";

        // Drop-down list for minutes
        $tag .= "<select title=\"Минуты\"
             $style $class
             type=\"text\"
             name='".$this->name."[minute]'>";
        for( $i = 0; $i <= 59; $i++ ) {
            if( $date_minute == $i ) $temp = "selected";
            else $temp = "";
            $tag .= "<option value=\"$i\" $temp>".sprintf( "%02d", $i );
        }
        $tag .= "</select>";
//            echo "<tt><pre>".print_r($tag, true)."</pre></tt>";

        if( $this->is_required ) $this->caption .= " *";

        // Form prompt
        $help = "";
        if( !empty( $this->help ) ) {
            $help .= "<span style='color:blue'>".
                nl2br( $this->help )."</span>";
        }
        if( !empty( $help ) ) $help .= "<br/>";
        if( !empty( $this->help_url) )  {
            $help .= "<span style='color: blue'><a href='".
                $this->help_url.">помощь</a></span>";
        }

        // Return array for class Form
        return array( $this->caption, $tag, $help );
    }

    /**
     * Check correct of date
     * @return string
     */
    public function check() {
        if( date( 'Y', $this->time ) > $this->end_year ||
            date( 'Y', $this->time ) < $this->begin_year )  {
            return "Поле \"".$this->caption."\" содержит
              недопустимые значения ( его значение
              должно лежать в диапазаоне ".
            $this->begin_year."-".$this->end_year." )";
        }
        return "";
    }
}
?>