<?php
/****************************************************************************************
* LiveZilla getfile.php
* 
* Copyright 2018 LiveZilla GmbH
* All rights reserved.
* LiveZilla is a registered trademark.
* 
* Improper changes to this file may cause critical errors.
***************************************************************************************/ 

define("IN_LIVEZILLA",true);

if(!defined("LIVEZILLA_PATH"))
	define("LIVEZILLA_PATH","./");

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require(LIVEZILLA_PATH . "_definitions/definitions.inc.php");
require(LIVEZILLA_PATH . "_definitions/definitions.protocol.inc.php");
require(LIVEZILLA_PATH . "_lib/functions.global.inc.php");
require(LIVEZILLA_PATH . "_definitions/definitions.dynamic.inc.php");

if(isset($_GET["id"]) && Server::InitDataProvider())
{
	$id = $_GET["id"];
	if(strpos($id,".") === false && !Is::Null($res = KnowledgeBaseEntry::GetById($id)))
	{
		if(file_exists("./uploads/" . $res["value"]) && strpos($res["value"],"..") === false)
		{
            if($res["title"] == "screenshot.lzsc")
            {
                $capdata = file_get_contents("./uploads/" . $res["value"]);
                $capdata = explode("|||",$capdata);

                if(isset($_GET["lzscrcap"]))
                {
                    exit("LiveZilla.Engine.ApplyScreenCapture('" . $capdata[0] . "','" . $capdata[2] . "');");
                }

                $html = IOStruct::GetFile(PATH_TEMPLATES . "capture.tpl");
                $html = str_replace("<!--data-->",$capdata[0],$html);
                $html = str_replace("<!--id-->",$id,$html);
                $html = str_replace("<!--w-->",$capdata[3],$html);
                $html = str_replace("<!--h-->",$capdata[4],$html);

                $url = base64_decode($capdata[1]);
                if(strpos($url,"?") === false)
                    $url .= "?acid=" . getId(10);
                else
                    $url .= "&acid=" . getId(10);

                if(Communication::GetScheme() == SCHEME_HTTP_SECURE)
                    if(strpos(strtolower($url),SCHEME_HTTP) === 0)
                        $url = str_replace(SCHEME_HTTP,SCHEME_HTTP_SECURE,$url);

                $html = str_replace("<!--name-->",getId(10),$html);
                $html = str_replace("<!--url-->",$url,$html);
                exit($html);
            }
            else
            {
                $mime = mime_content_type("./uploads/" . $res["value"]);
                if(empty($mime))
                    $mime = "application/octet-stream";

                header('Content-Description: File Transfer');
                header('Content-Type: ' . $mime);
                header('Content-Length: ' . filesize("./uploads/" . $res["value"]));
                header('Content-Disposition: attachment; filename=' . urlencode($res["title"]));
                exit(file_get_contents("./uploads/" . $res["value"]));
            }
		}
	}
}
header("HTTP/1.0 404 Not Found");
?>