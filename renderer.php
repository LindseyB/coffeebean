<?php 


// Code Renderer Library

// Dave Wilkinson
// (c) 2007

// Contains functions to render code for website usage.

// implement these CSS classes:
//   codeString     -- strings
//   codeOp         -- operators
//   codeComment    -- comments
//   codeKeyword    -- keywords
//   codeNumber     -- number
//   codePreProp    -- preprocessor
//   codeText       -- normal text (HTML)
//   codeAttribute  -- attributes (HTML)


// Will return a string of rendered code
function RenderToString($f, $lang, $renderSpacesAsNonBreaking = true)
{

//syntax highlighting




// IMPLEMENT LANGUAGES HERE:


// the parser to use
$parser = 0;


if($lang == 1)
{

// BASIC


$keywords[] = array("If", "False", "True", "Else", "Integer", "Boolean", "Double", "Single", "Byte", "Len", "LenB", "Type", "Property", "Friend", "Long", "Object", "Variant", "String", "Currency", "Static", "Any", "ElseIf", "Select", "Case", "Exit", "Class", "End", "Function", "New", "Nothing", "Is", "Public", "Private", "Sub", "As", "Dim", "ReDim", "Preserve", "Declare", "Lib", "While", "ByVal", "ByRef", "To", "GoTo", "Option", "Explicit", "Then", "Binary", "Access", "Read", "Write", "Random", "Open", "Close", "Get", "Put", "For", "Loop", "Next", "Wend", "Mod", "And", "Or", "Xor", "Not", "Alias");

$operators[] = array(".", "+", "-", "!", "%", ">", "<", "&", ":", "*", "/", "=");

$delimiters[] = array(" ", ";", ":", "[", "]", "(", ")", "+", "-", "!", "<", ">", "~", "{", "}", "*", "=", "/", "^", "&", "|", "%", ",", ".", '"', "'", "\t", "\n", "\r");

$prepros[] = array("VERSION", "Attribute");

$line_comment[] = str_split("'");

$line_comment_start[] = str_split("");

$line_comment_end[] = str_split("");

$allow_apost_strings = 0;

$capture_escape = 0;

$hasSwitcher = 0;

}

else if ($lang == 2)
{

// JAVA

$keywords[] = array("if", "false", "default", "true", "else", "return", "int", "boolean", "double", "float", "short", "long", "unsigned", "signed", "null", "const", "static", "char", "switch", "case", "class", "extends", "new", "void", "public", "protected", "private", "for", "do", "while", "break", "continue", "goto", "try", "catch", "throw", "throws", "this", "abstract", "assert", "byte", "finally", "final", "implements", "extends", "instanceof", "interface", "native", "package", "strictfp", "super", "synchronized", "transient", "volatile");

$operators[] = array(".", "+", "-", "!", "%", ">", "<", "~", ":", "*", "/", "^", "&", "|", "=", "[", "]");

$delimiters[] = array(" ", ";", ":", "[", "]", "(", ")", "+", "-", "!", "<", ">", "~", "{", "}", "*", "=", "/", "^", "&", "|", "%", ",", ".", '"', "'", "\t", "\n", "\r");

$prepros[] = array("import");

$line_comment[] = str_split("//");

$line_comment_start[] = str_split("/*");

$line_comment_end[] = str_split("*/");

$allow_apost_strings = 1;

$capture_escape[] = 1;

$hasSwitcher = 0;

}

else if ($lang == 3 || $lang == 4)
{

if ($lang == 4)
{

// PHP

$keywords[] = array("if", "false", "default", "true", "else", "return", "int", "boolean", "double", "float", "short", "long", "unsigned", "signed", "null", "const", "static", "char", "switch", "case", "class", "extends", "new", "void", "public", "protected", "private", "for", "do", "while", "break", "continue", "goto", "try", "catch", "throw", "throws", "this", "abstract", "assert", "byte", "finally", "final", "implements", "extends", "instanceof", "interface", "native", "package", "strictfp", "super", "synchronized", "transient", "volatile", "\$_SESSION", "\$_POST", "\$_SERVER", "include");

$operators[] = array(".", "+", "-", "!", "%", ">", "<", "~", ":", "*", "/", "^", "&", "|", "=", "[", "]");

$delimiters[] = array(" ", ";", ":", "[", "]", "(", ")", "+", "-", "!", "<", ">", "~", "{", "}", "*", "=", "/", "^", "&", "|", "%", ",", ".", '"', "'", "\t", "\n", "\r");

$prepros[] = array("import");

$line_comment[] = str_split("//");

$line_comment_start[] = str_split("/*");

$line_comment_end[] = str_split("*/");

$allow_apost_strings = 1;

$capture_escape[] = 1;

$hasSwitcher = 1;

$switcher = str_split("?>");
$switcher_len = count($switcher);
$switchTo = 1;
$switchOutput = '<span class="codeKeyword">?</span><span class="codeOp">&gt;</span>';

}

// HTML

$keywords[] = array("a", "abbr", "acronym", "address", "applet", "area", "b", "base", "basefont", "bdo", "big", "blockquote", "body", "br", "button", "caption", "center", "cite", "code", "col", "colgroup", "dd", "del", "dfn", "dir", "div", "dl", "dt", "em", "fieldset", "font", "form", "frame", "frameset", "h1", "h2", "h3", "h4", "h5", "h6", "head", "hr", "html", "iframe", "img", "input", "ins", "isindex", "kbd", "label", "legend", "li", "link", "map", "menu", "meta", "noframes", "noscript", "object", "ol", "optgroup", "option", "p", "param", "pre", "q", "s", "samp", "script", "select", "small", "span", "strike", "strong", "style", "sub", "sup", "table", "tbody", "td", "textarea", "tfoot", "th", "thead", "title", "tr", "tt", "u", "ul", "var");
$attributes = array("abbr", "accept-charset", "accept", "accesskey", "action", "align", "alink", "alt", "archive", "axis", "background", "bgcolor", "border", "cellpadding", "cellspacing", "char", "charoff", "charset", "checked", "cite", "class", "classid", "clear", "code", "codebase", "codetype", "color", "cols", "colspan", "compact", "content", "coords", "data", "datetime", "declare", "defer", "dir", "disabled", "enctype", "face", "for", "frame", "frameborder", "headers", "height", "href", "hreflang", "hspace", "http-equiv", "id", "ismap", "label", "lang", "language", "link", "longdesc", "marginheight", "marginwidth", "maxlength", "media", "method", "multiple", "name", "nohref", "noresize", "noshade", "nowrap", "object", "onblur", "onchange", "onclick", "ondblclick", "onfocus", "onkeydown", "onkeypress", "onkeyup", "onload", "onmousedown", "onmousemove", "onmouseout", "onmouseover", "onmouseup", "onreset", "onselect", "onsubmit", "onunload", "profile", "prompt", "readonly", "rel", "rev", "rows", "rowspan", "rules", "scheme", "scope", "scrolling", "selected", "shape", "size", "span", "src", "standby", "start", "style", "summary", "tabindex", "target", "text", "title", "type", "usemap", "valign", "value", "valuetype", "version", "vlink", "vspace", "width");

$operators[] = array(".", "+", "!", "%", ">", "<", "~", ":", "*", "/", "^", "&", "|", "=", "[", "]");

$delimiters[] = array(" ", ";", ":", "[", "]", "(", ")", "+", "!", "<", ">", "~", "{", "}", "*", "=", "/", "^", "&", "|", "%", ",", ".", '"', "'", "\t", "\n", "\r");

$prepros[] = array("import");

$line_comment[] = str_split("//");

$line_comment_start[] = str_split("/*");

$line_comment_end[] = str_split("*/");

$capture_escape[] = 1;

$parser = 1;

}

else //$lang == 0

{

// C, C++

$keywords[] = array("asm", "auto", "bool", "break", "case", "catch", "char", "class", "const", "const_cast", "continue", "default", "delete", "do", "double", "dynamic_cast", "else", "enum", "explicit", "export", "extern", "false", "float", "for", "friend", "goto", "if", "inline", "int", "long", "mutable", "namespace", "new", "operator", "private", "protected", "public", "register", "reinterpret_cast", "return", "short", "signed", "sizeof", "static", "static_cast", "struct", "switch", "template", "this", "throw", "true", "try", "typedef", "typeid", "typename", "union", "unsigned", "using", "virtual", "void", "volatile", "wchar_t", "while");

$operators[] = array(".", "+", "-", "!", "%", ">", "<", "~", ":", "*", "/", "^", "&", "|", "=", "[", "]");

$delimiters[] = array(" ", ";", ":", "[", "]", "(", ")", "+", "-", "!", "<", ">", "~", "{", "}", "*", "=", "/", "^", "&", "|", "%", ",", ".", '"', "'", "\t", "\n", "\r");

$prepros[] = array("#include", "#define", "#if", "#else", "#elif", "#endif", "#pragma", "#ifdef", "#ifndef", "#undef");

$line_comment[] = str_split("//");

$line_comment_start[] = str_split("/*");

$line_comment_end[] = str_split("*/");

$allow_apost_strings = 1;

$capture_escape[] = 1;

$hasSwitcher = 0;

}



for($i = 0; $i < count($operators); $i++)
{

$operators_count[] = count($operators[$i]);

$delimiter_count[] = count($delimiters[$i]);

$prepros_count[] = count($prepros[$i]);

$line_comment_len[] = count($line_comment[$i]);

$line_comment_start_len[] = count($line_comment_start[$i]);

$line_comment_end_len[] = count($line_comment_end[$i]);

}

if (isset($attributes))
{
    $attributes_len = count($attributes);
}

$toadd = NULL;

$ci=0;

$str = "";

if ($lang == -1)
{

    $str = $f;

    $f = str_split($f);

    $f_count = count($f);

    $ci = $f_count;
}
else
{
    $f = str_split($f);

    $f_count = count($f);
}

$token = "";

$i;

$notparsed = true;

$curCol = -1;

while ($notparsed)
{

switch ($parser)
{

case 0:

// LANGUAGE RENDERER

//states
$inquote=false;
$inline=false;
$commentblock = false;

$gotoinline = false;
$gotoinquote = false;
$gotocommentblock = false;

// for tabbing
$tabsize = 4;
$numspacesneeded = 0;

$quoteType = 0;

$rep_char;

$gotoadd;

for( ; $ci < $f_count; $ci++)
{
    if ($f[$ci] == "\n")
    {
        $curCol = -1;
    }
    else
    {
        $curCol++;
    }

    if ($f[$ci] == " ")
    {
        if ($renderSpacesAsNonBreaking) {
            $rep_char = "&nbsp;";
        } else {        
            if ($ci + 1 < $f_count && $f[$ci+1] == " ") {
                $rep_char = "&nbsp;";
            } else {
                $rep_char = " ";
            }
        }
    }
    else if ($f[$ci] == "\t")
    {
        //look at curCol to determine number of spaces

        $numspacesneeded = 4 - ($curCol % $tabsize);

        $curCol+=$numspacesneeded-1;

        $rep_char = "";

        if (!$renderSpacesAsNonBreaking)
        {
            $numspacesneeded--;
        }

        for ( ; $numspacesneeded > 0; $numspacesneeded--)
        {
            $rep_char.='&nbsp;';
        }

        if (!$renderSpacesAsNonBreaking && $numspacesneeded == 0)
        {
            $rep_char.=' ';
        }

    }
    else if ($f[$ci] == '<')
    {
        $rep_char = '&lt;';
    }
    else if ($f[$ci] == '>')
    {
        $rep_char = '&gt;';
    }
    else if ($f[$ci] == '&')
    {
        $rep_char = '&amp;';
    }
    else if ($f[$ci] == "\n")
    {
        $rep_char = "<br>\n";
    }
    else
    {
        $rep_char = $f[$ci];
    }

    if ($inline)
    {
        $token .= $rep_char;

        if ($f[$ci] == '\\' && $capture_escape[$parser])
        {
            $ci++;
            if ($f[$ci] == "\r")
            {
                $token .= "<br>\n";
                $ci++;
                $curCol = -1;
            }
            else if ($f[$ci] == "\n")
            {
                $curCol = -1;
            }
            else
            {
                $token .= $f[$ci];          
            }
        }
        else if ($f[$ci] == "\n")
        {   
            $str.= $token.'</span>';
            $token = "";
            $inline = false;
            $curCol = -1;
        }
    }
    else if ($commentblock)
    {
        $token .= $rep_char;
        if ($f[$ci] == '\\' && $capture_escape[$parser])
        {
            $ci++;
            $curCol++;
            if ($f[$ci] == "\r")
            {
                $token .= "<br>\n";
                $ci++;
                $curCol++;
            }
            $token .= $f[$ci];      
        }
        else
        {
            $temp_index = $ci;
            $toadd = "";
            for ($i=0; $i<$line_comment_end_len[$parser] && $temp_index < $f_count; $i++, $temp_index++)
            {
                if ($f[$temp_index] != $line_comment_end[$parser][$i])
                {
                    //bad (no comment)
                    break;
                }
                if ($i != 0) { $toadd .= $line_comment_end[$parser][$i]; }
            }
            if ($i == $line_comment_end_len[$parser])
            {
                //if ($renderSpacesAsNonBreaking == 1) { $token = str_replace(" ", "&nbsp;", $token); } 
                $str.= $token.$toadd.'</span>';
                $token = "";
                $commentblock = false;  

                $ci += $line_comment_end_len[$parser];      
            }
        }
    }
    else if ($inquote)
    {
        $token .= $rep_char;

        if ($f[$ci] == '\\' && $capture_escape[$parser])
        {
            $ci++;
            $curCol++;
            if ($f[$ci] == "\r")
            {
                $token .= "<br>\n";
                $ci++;
                $curCol++;
            }
            $token .= $f[$ci];
        }
        else if ($f[$ci] == "\n")
        {
            $str.='<span class="codeString">'.$token.'</span>';
            $token = "";
            $inquote = false;
            $curCol = -1;
        }
        else if ($quoteType == 0 && $f[$ci] == '"')
        {
            $str.='<span class="codeString">'.$token.'</span>';
            $token = "";
            $inquote = false;
        }
        else if ($quoteType == 1 && $f[$ci] == "'")
        {
            $str.='<span class="codeString">'.$token.'</span>';
            $token = "";
            $inquote = false;
        }
    }
    else
    {
        //compare with switcher (if any)
        
        if ($hasSwitcher)
        {
            $temp_index = $ci;
            for ($i=0; $i<$switcher_len && $temp_index < $f_count; $i++, $temp_index++)
            {
                if ($f[$temp_index] != $switcher[$i])
                {
                    // no switcher
                    break;
                }
            }
            if ($i == $switcher_len)
            {
                //switch to another parser
                
                $parser = $switchTo;
                $str.=$switchOutput;
                $ci += $switcher_len;
                $curCol += $switcher_len;
                break;
            }
        }
        
        //compare with line comment starts

        $temp_index = $ci;
        for ($i=0; $i<$line_comment_len[$parser] && $temp_index < $f_count; $i++, $temp_index++)
        {
            if ($f[$temp_index] != $line_comment[$parser][$i])
            {
                //bad (no comment)
                break;
            }
        }
        if ($i == $line_comment_len[$parser])
        {
            $gotoadd = '<span class="codeComment">';
            $gotoinline = true;
        }

        $temp_index = $ci;
        for ($i=0; $i<$line_comment_start_len[$parser] && $temp_index < $f_count; $i++, $temp_index++)
        {
            if ($f[$temp_index] != $line_comment_start[$parser][$i])
            {
                //bad (no comment)
                break;
            }
        }
        if ($i == $line_comment_start_len[$parser])
        {
            $gotoadd = '<span class="codeComment">'.$rep_char;
            $gotocommentblock = true;
        }

        for($i=0; $i<$delimiter_count[$parser]; $i++)
        {
            if ($f[$ci] == $delimiters[$parser][$i])
            {
                $toadd="";
                
                foreach($keywords[$parser] as $keyword)
                { //keyword
                    if (!strcmp($token, $keyword))
                    {   
                        $toadd = '<span class="codeKeyword">'.$token.'</span>';
                        break;
                    }
                }
                if ($toadd == NULL)
                { //preprocessor
                    foreach($prepros[$parser] as $pre_p)
                    {
                        if (!strcmp($token, $pre_p))
                        {
                            $toadd = '<span class="codePreProp">'.$token;
                            $gotoinline = true;
                            break;
                        }
                    }
                }
                if ($toadd == NULL)
                { //numbers
                    $arr = str_split($token);
                    $token_count = count($arr);

                    $ti = 0;

                    $hex = false;

                    $pointfound=false;

                    if ($token_count > 2)
                    {
                        if ($arr[0] == '0' && ($arr[1] == 'x' || $arr[1] == 'X'))
                        {
                            //hex possible
                            $ti = 2;
                            $hex = true;
                        }
                    }
                    
                    if ($hex)
                    {
                        for ( ; $ti < $token_count; $ti++)
                        {
                            if (($arr[$ti] < '0' || $arr[$ti] > '9') && ($arr[$ti] < 'a' || $arr[$ti] > 'f') && ($arr[$ti] < 'A' || $arr[$ti] > 'F'))
                            {
                                //bad
                                break;
                            }
                        }
                    }
                    else
                    {
                        for ( ; $ti < $token_count; $ti++)
                        {
                            if ($arr[$ti] == '.')
                            {
                                if ($pointfound)
                                {
                                    break;
                                }

                                $pointfound = true;
                            }
                            else if ($arr[$ti] < '0' || $arr[$ti] > '9')
                            {
                                //bad
                                break;
                            }
                        }
                    }
                    if ($ti == $token_count)
                    {
                        //number
                        $toadd = '<span class="codeNumber">'.$token.'</span>';
                    }
                }
                if ($toadd == NULL) 
                {                       
                    $toadd = $token;
                }

                $token="";

                $str .= $toadd;

                break;
            }
        }
        if ($inline || $gotoinline)
        {
            $token = $rep_char;
        }
        else if ($f[$ci] == '\\' && $capture_escape[$parser])
        {
            $token .= $f[$ci];
            $ci++;
            $curCol++;
            if ($f[$ci] == "\n")
            {
                $token.="<br>\n";
            }
            else
            {
                $token.= $f[$ci];
            }
        }           
        else if ($f[$ci] == '"')
        {   
            $gotoinquote = true;
            
            $quoteType = 0;

            $token = $f[$ci];
        }
        else if ($f[$ci] == "'" && $allow_apost_strings)
        {
            $gotoinquote = true;
            
            $quoteType = 1;

            $token = $f[$ci];
        }
        else if ($gotoinquote || $gotocommentblock)
        {
            $token = "";
        }
        else
        {
            if ($i==$delimiter_count[$parser]) 
            { 
                $token.=$rep_char; 
            }
            else
            {
                //add delimiter
                for ($i=0; $i<$operators_count[$parser]; $i++)
                {
                    if ($f[$ci] == $operators[$parser][$i])
                    {
                        $str.='<span class="codeOp">'.$rep_char.'</span>';
                        break;
                    }           
                }
                if ($i == $operators_count[$parser])
                {   
                    $str.=$rep_char; //$f[$ci];
                }
            }
        }

        if ($gotoinline == true) 
        { 
            $str.=$gotoadd; $inline = true; $gotoinline=false;
        }
        if ($gotoinquote == true) { $inquote = true; $gotoinquote=false;}
        if ($gotocommentblock == true) 
        { 
            $gotocommentblock = false;
            $str .= $gotoadd;
            $commentblock = true; 
        }

        $gotoadd = "";
    }
}

if ($parser == 0)
{
    $notparsed = false;
}

break;

case 1:

// TAG RENDERER

//states
$intag = false;
$inquote = false;
$gotoinquote = false;

$tagname = "";

$str.='<span class="codeText">';

for( ; $ci < $f_count; $ci++)
{
    if ($f[$ci] == "\n")
    {
        $curCol = -1;
    }
    else
    {
        $curCol++;
    }

    if ($f[$ci] == " ")
    {
        if ($renderSpacesAsNonBreaking) {
            $rep_char = "&nbsp;";
        } else {        
            if ($ci + 1 < $f_count && $f[$ci+1] == " ") {
                $rep_char = "&nbsp;";
            } else {
                $rep_char = " ";
            }
        }
    }
    else if ($f[$ci] == "\t")
    {
        //look at curCol to determine number of spaces

        $numspacesneeded = 4 - ($curCol % $tabsize);

        $curCol+=$numspacesneeded-1;

        $rep_char = "";

        if (!$renderSpacesAsNonBreaking)
        {
            $numspacesneeded--;
        }

        for ( ; $numspacesneeded > 0; $numspacesneeded--)
        {
            $rep_char.='&nbsp;';
        }

        if (!$renderSpacesAsNonBreaking && $numspacesneeded == 0)
        {
            $rep_char.=' ';
        }

    }
    else if ($f[$ci] == '<')
    {
        $rep_char = '&lt;';
    }
    else if ($f[$ci] == '>')
    {   
        $rep_char = '&gt;';
    }
    else if ($f[$ci] == '&')
    {
        $rep_char = '&amp;';
    }
    else if ($f[$ci] == "\n")
    {
        $rep_char = "<br>\n";
    }
    else
    {
        $rep_char = $f[$ci];
    }

    if ($inquote)
    {       
        $token .= $rep_char;

        if ($f[$ci] == '\\' && $capture_escape[$parser])
        {
            $ci++;
            if ($f[$ci] == "\r")
            {
                $token .= $f[$ci];
                $ci++;
            }
            $token .= $f[$ci];
        }
        else if ($f[$ci] == "\n")
        {
            $str.='<span class="codeString">'.$token.'</span>';
            $token = "";
            $curCol = -1;
            $inquote = false;
        }
        else if ($f[$ci] == '"')
        {
            $str.='<span class="codeString">'.$token.'</span>';
            $token = "";
            $inquote = false;
        }
    }
    else if ($intag)
    {
        // parsing tags
        // each keyword should be checked

        // check for delimiters

        for($i=0; $i<$delimiter_count[$parser]; $i++)
        {
            if ($f[$ci] == $delimiters[$parser][$i])
            {
                $toadd="";
                
                if ($tagname == "")
                {
                    $tagname = $token;
                    
                    if ($lang == 4 && $tagname == "?php")
                    {
                        // GOTO PHP PROCESSOR
                        $str.='<span class="codeKeyword">?php</span>';
                        
                        $token = "";
                        
                        $parser = 0;
                        
                        break;                      
                    }
                
                    foreach($keywords[$parser] as $keyword)
                    { //keyword
                        if (!strcmp($token, $keyword))
                        {                           
                            $toadd = '<span class="codeKeyword">'.$token.'</span>';
                            break;
                        }
                    }
                }
                if ($toadd == NULL)
                {
                    foreach($attributes as $attr)
                    { //keyword
                        if (!strcmp($token, $attr))
                        {                           
                            $toadd = '<span class="codeAttribute">'.$token.'</span>';
                            break;
                        }
                    }
                }
                if ($toadd == NULL)
                { //numbers
                    $arr = str_split($token);
                    $token_count = count($arr);

                    $ti = 0;

                    $hex = false;

                    $pointfound=false;

                    if ($token_count > 2)
                    {
                        if ($arr[0] == '0' && ($arr[1] == 'x' || $arr[1] == 'X'))
                        {
                            //hex possible
                            $ti = 2;
                            $hex = true;
                        }
                    }
                    
                    if ($hex)
                    {
                        for ( ; $ti < $token_count; $ti++)
                        {
                            if (($arr[$ti] < '0' || $arr[$ti] > '9') && ($arr[$ti] < 'a' || $arr[$ti] > 'f') && ($arr[$ti] < 'A' || $arr[$ti] > 'F'))
                            {
                                //bad
                                break;
                            }
                        }
                    }
                    else
                    {
                        for ( ; $ti < $token_count; $ti++)
                        {
                            if ($arr[$ti] == '.')
                            {
                                if ($pointfound)
                                {
                                    break;
                                }

                                $pointfound = true;
                            }
                            else if ($arr[$ti] < '0' || $arr[$ti] > '9')
                            {
                                //bad
                                break;
                            }
                        }
                    }
                    if ($ti == $token_count)
                    {
                        //number
                        $toadd = '<span class="codeNumber">'.$token.'</span>';
                    }
                }
                if ($toadd == NULL) 
                {
                    $toadd = $token;
                }

                $token="";

                $str .= $toadd;

                break;
            }
        }
        if ($parser != 1)
        {
            break;
        }
        else if ($f[$ci] == '\\' && $capture_escape[$parser])
        {
            $token .= $f[$ci];
            $ci++;
            $token .= $f[$ci];
        }           
        else if ($f[$ci] == '"')
        {   
            $gotoinquote = true;

            $token = $f[$ci];
        }
        else if ($f[$ci] == '>')
        {
            $str.='<span class="codeOp">&gt;</span><span class="codeText">';
            $token = "";
            $intag = false;
            $tagname = "";
        }
        else if ($gotoinquote || $gotocommentblock)
        {
            $token = "";
        }
        else
        {
            if ($i==$delimiter_count[$parser]) 
            { 
                $token.=$rep_char; 
            }
            else
            {
                //add delimiter
                for ($i=0; $i<$operators_count[$parser]; $i++)
                {
                    if ($f[$ci] == $operators[$parser][$i])
                    {
                        $str.='<span class="codeOp">'.$f[$ci].'</span>';
                        break;
                    }           
                }
                if ($i == $operators_count[$parser])
                {
                    $str.=$rep_char;
                }
            }
        }

        if ($gotoinquote == true) { $inquote = true; $gotoinquote=false;}
    }
    else
    {
        if ($f[$ci] == '<')
        {
            $str.=$token;
            $str.='</span><span class="codeOp">&lt;</span>';
            $token = "";
            $intag = true;

            continue;
        }

        $token.=$rep_char;
    }

}

if ($parser == 1)
{
    $notparsed = false;
}

break;

}

}

if ($inquote)
{
    $str .= $token;
}
else if ($inline)
{
    $str .= $token.'</span>';
}
else
{
    $str .= $token;
}

return $str;

}

// Will render from a file
function RenderFromFile($fn, $renderSpacesAsNonBreaking = true)
{

//if (stristr($fn, 'php')) return -1;

if (stristr($fn, '..')) return -1;

if (file_exists($fn) == FALSE) return -1;
else
{

    $f = file_get_contents($fn);
    
}


// FILE TYPES GO HERE:

$ext_check = array( array("cpp", "c", "hpp", "h"), array("frm", "bas", "cls"), array("java"), array("html"), array("php") );

$lang_count = count($ext_check);

//check extention

$path_parts = pathinfo($fn);

$lang = -1;

for($num_exts=0; $num_exts < $lang_count; $num_exts++)
{

    $num_ind = count($ext_check[$num_exts]);

    for($i=0; $i<$num_ind;$i++)
    {

        if ($path_parts['extension'] == $ext_check[$num_exts][$i])
        {
            $lang=$num_exts;
            $num_exts = $lang_count;
            break;
        }
    }

}






$str = RenderToString($f, $lang, $renderSpacesAsNonBreaking);

return $str;

}


// Will Render from a File, if it needs to
// and the create a new file with a .render extension
// in a new directory called '.render' based in the 
// current directory of the file given

// ex: RenderFromFileToRenderFile('files/code.cpp');
//
// will parse code.cpp and store code.cpp.render in:
//    files/.render/

// it will return the path to this rendered file
// you can open this and return the contents

function RenderFromFileToRenderFile($fn, $renderSpacesAsNonBreaking = true)
{

//put it in the '.render' directory

$p = strtok($fn, "/");

$newdir = "";

while ($p !== FALSE)
{
    $cur = $p;
    $p = strtok("/");
    if ($p === FALSE) { break; }
    $newdir .= $cur."/";
}

//$cur is the file name
$cur .= '.render';

$newdir .= '.render';

if (!file_exists($newdir))
{
    mkdir($newdir);
}

$newdir .= '/';

$newfn = $newdir.$cur;

if (file_exists($newfn))
{
    return $newfn;
}

$str = RenderFromFile($fn, $renderSpacesAsNonBreaking);

file_put_contents($newfn, $str);

return $newfn;

}

function ForceRenderFromFileToRenderFile($fn, $renderSpacesAsNonBreaking = true)
{

$str = RenderFromFile($fn, $renderSpacesAsNonBreaking);

//put it in the '.render' directory

$p = strtok($fn, "/");

$newdir = "";

while ($p !== FALSE)
{
    $cur = $p;
    $p = strtok("/");
    if ($p === FALSE) { break; }
    $newdir .= $cur."/";
}

//$cur is the file name
$cur .= '.render';

$newdir .= '.render';

if (!file_exists($newdir))
{
    mkdir($newdir);
}

$newdir .= '/';

$newfn = $newdir.$cur;

if (file_exists($newfn))
{
    unlink($newfn);
}

file_put_contents($newfn, $str);

return $newfn;

}

function RenderFromFileToFile($fn, $newfn, $renderSpacesAsNonBreaking = true)
{

$str = RenderFromFile($fn, $renderSpacesAsNonBreaking);

if (file_exists($newfn))
{
    unlink($newfn);
}

file_put_contents($newfn, $str);

}

?>