<?php
$reset_template = <<<RESETEMAIL
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="initial-scale=1.0">
  <meta name="format-detection" content="telephone=no">
  <title>Password Reset</title>

  <style type="text/css">
    body{ Margin: 0; padding: 0; }
    img{ border: 0px; display: block; }

    .socialLinks{ font-size: 6px; }
    .socialLinks a{
      display: inline-block;
    }
    .socialIcon{
      display: inline-block;
      vertical-align: top;
      padding-bottom: 0px;
      border-radius: 100%;
    }
    .oldwebkit{ max-width: 570px; }
    td.vb-outer{ padding-left: 9px; padding-right: 9px; }
    table.vb-container, table.vb-row, table.vb-content{
      border-collapse: separate;
    }
    table.vb-row{
      border-spacing: 9px;
    }
    table.vb-row.halfpad{
      border-spacing: 0;
      padding-left: 9px;
      padding-right: 9px;
    }
    table.vb-row.fullwidth{
      border-spacing: 0;
      padding: 0;
    }
    table.vb-container{
      padding-left: 18px;
      padding-right: 18px;
    }
    table.vb-container.fullpad{
      border-spacing: 18px;
      padding-left: 0;
      padding-right: 0;
    }
    table.vb-container.halfpad{
      border-spacing: 9px;
      padding-left: 9px;
      padding-right: 9px;
    }
    table.vb-container.fullwidth{
      padding-left: 0;
      padding-right: 0;
    }
     .verifybtn {
        color: #ffffff;
         text-decoration: none;

      }

.linkwrap {

  /* These are technically the same, but use both */
  overflow-wrap: break-word;
  word-wrap: break-word;

  -ms-word-break: break-all;
  /* This is the dangerous one in WebKit, as it breaks things wherever */
  word-break: break-all;
  /* Instead use this non-standard one: */
  word-break: break-word;

  /* Adds a hyphen where the word breaks, if supported (No Blink) */
  -ms-hyphens: auto;
  -moz-hyphens: auto;
  -webkit-hyphens: auto;
  hyphens: auto;

}

</style>
  <style type="text/css">
    /* yahoo, hotmail */
    .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div{ line-height: 100%; }
    .yshortcuts a{ border-bottom: none !important; }
    .vb-outer{ min-width: 0 !important; }
    .RMsgBdy, .ExternalClass{
      width: 100%;
}

    /* outlook */
    table{ mso-table-rspace: 0pt; mso-table-lspace: 0pt; }
    #outlook a{ padding: 0; }
    img{ outline: none; text-decoration: none; border: none; -ms-interpolation-mode: bicubic; }
    a img{ border: none; }

    @media screen and (max-device-width: 600px), screen and (max-width: 600px) {
      table.vb-container, table.vb-row{
        width: 95% !important;
      }

      .mobile-hide{ display: none !important; }
      .mobile-textcenter{ text-align: center !important; }

      .mobile-full{
        float: none !important;
        width: 100% !important;
        max-width: none !important;
        padding-right: 0 !important;
        padding-left: 0 !important;
      }
      img.mobile-full{
        width: 100% !important;
        max-width: none !important;
        height: auto !important;
      }
    }
  </style>
  <style type="text/css">

    #ko_textBlock_6 .links-color a, #ko_textBlock_6 .links-color a:link, #ko_textBlock_6 .links-color a:visited, #ko_textBlock_6 .links-color a:hover{
      color: #3f3f3f;
      color: #3f3f3f;
      text-decoration: underline
    }
     #ko_textBlock_6 .long-text p{ Margin: 1em 0px }  #ko_textBlock_6 .long-text p:last-child{ Margin-bottom: 0px }  #ko_textBlock_6 .long-text p:first-child{ Margin-top: 0px }
    #ko_footerBlock_2 .links-color a, #ko_footerBlock_2 .links-color a:link, #ko_footerBlock_2 .links-color a:visited, #ko_footerBlock_2 .links-color a:hover{
      color: #cccccc;
      color: #cccccc;
      text-decoration: underline
    }
     #ko_footerBlock_2 .long-text p{ Margin: 1em 0px }  #ko_footerBlock_2 .long-text p:last-child{ Margin-bottom: 0px }  #ko_footerBlock_2 .long-text p:first-child{ Margin-top: 0px } </style>
</head>
<body bgcolor="#3f3f3f" text="#919191" alink="#cccccc" vlink="#cccccc" style="Margin: 0; padding: 0; color: #919191;">

  <center>

  <table class="vb-outer" width="100%" cellpadding="0" border="0" cellspacing="0"  id="ko_logoBlock_4">
    <tbody><tr>
      <td class="vb-outer" align="center" valign="top" style="padding-left: 9px; padding-right: 9px;">

<!--[if (gte mso 9)|(lte ie 8)]><table align="center" border="0" cellspacing="0" cellpadding="0" width="570"><tr><td align="center" valign="top"><![endif]-->
    <div class="oldwebkit" style="max-width: 570px;">
    <table width="570" style="border-collapse: separate; border-spacing: 18px; padding-left: 0; padding-right: 0; width: 100%; max-width: 570px;" border="0" cellpadding="0" cellspacing="18" class="vb-container fullpad">
      <tbody><tr>
        <td valign="top" align="center">

<!--[if (gte mso 9)|(lte ie 8)]><table align="center" border="0" cellspacing="0" cellpadding="0" width="350"><tr><td align="center" valign="top"><![endif]-->
<div class="mobile-full" style="display: inline-block; max-width: 350px; vertical-align: top; width: 100%;">

<img width="350" vspace="0" hspace="0" border="0" alt="" style="border: 0px; display: block; width: 100%; max-width: 350px;" src="$this->mainlogo">


</div>
<!--[if (gte mso 9)|(lte ie 8)]></td></tr></table><![endif]-->

            </td>
          </tr>
        </tbody></table>
        </div>
<!--[if (gte mso 9)|(lte ie 8)]></td></tr></table><![endif]-->

      </td>
    </tr>
  </tbody></table><table class="vb-outer" width="100%" cellpadding="0" border="0" cellspacing="0" id="ko_titleBlock_5">
    <tbody><tr>
      <td class="vb-outer" align="center" valign="top"  style="padding-left: 9px; padding-right: 9px;">

<!--[if (gte mso 9)|(lte ie 8)]><table align="center" border="0" cellspacing="0" cellpadding="0" width="570"><tr><td align="center" valign="top"><![endif]-->
        <div class="oldwebkit" style="max-width: 570px;">
        <table width="570" border="0" cellpadding="0" cellspacing="9" class="vb-container halfpad" bgcolor="#ffffff" style="border-collapse: separate; border-spacing: 9px; padding-left: 9px; padding-right: 9px; width: 100%; max-width: 570px; background-color: #ffffff;">
          <tbody><tr>
            <td bgcolor="#ffffff" align="center" style="background-color: #ffffff; font-size: 22px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f; text-align: center;">
              <span>$this->site_name - Reset Password</span>
            </td>
          </tr>
        </tbody></table>
        </div>
<!--[if (gte mso 9)|(lte ie 8)]></td></tr></table><![endif]-->
      </td>
    </tr>
  </tbody></table><table class="vb-outer" width="100%" cellpadding="0" border="0" cellspacing="0"  id="ko_textBlock_6">
    <tbody><tr>
      <td class="vb-outer" align="center" valign="top"  style="padding-left: 9px; padding-right: 9px;">

<!--[if (gte mso 9)|(lte ie 8)]><table align="center" border="0" cellspacing="0" cellpadding="0" width="570"><tr><td align="center" valign="top"><![endif]-->
        <div class="oldwebkit" style="max-width: 570px;">
        <table width="570" border="0" cellpadding="0" cellspacing="18" class="vb-container fullpad" bgcolor="#ffffff" style="border-collapse: separate; border-spacing: 18px; padding-left: 0; padding-right: 0; width: 100%; max-width: 570px; background-color: #ffffff;">
          <tbody><tr>
            <td align="left" class="long-text links-color" style="text-align: left; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;">
              <p style="Margin: 1em 0px; Margin-top: 0px;">
                <center>
                $this->reset_email
                </center>
                </p>
            </td>
          </tr>
        </tbody></table>
        </div>
<!--[if (gte mso 9)|(lte ie 8)]></td></tr></table><![endif]-->
      </td>
    </tr>
  </tbody></table><table class="vb-outer" width="100%" cellpadding="0" border="0" cellspacing="0" id="ko_buttonBlock_7">
    <tbody><tr>
      <td class="vb-outer" align="center" valign="top"  style="padding-left: 9px; padding-right: 9px;">

<!--[if (gte mso 9)|(lte ie 8)]><table align="center" border="0" cellspacing="0" cellpadding="0" width="570"><tr><td align="center" valign="top"><![endif]-->
        <div class="oldwebkit" style="max-width: 570px;">
        <table width="570" border="0" cellpadding="0" cellspacing="18" class="vb-container fullpad" bgcolor="#ffffff" style="border-collapse: separate; border-spacing: 18px; padding-left: 0; padding-right: 0; width: 100%; max-width: 570px; background-color: #ffffff;">
          <tbody><tr>
            <td valign="top" bgcolor="#ffffff" align="center" style="background-color: #ffffff;">

              <table cellpadding="0" border="0" align="center" cellspacing="0" class="mobile-full">
                <tbody><tr>
                  <td width="auto" valign="middle" bgcolor="#bfbfbf" align="center" height="50" style="font-size: 22px; font-family: Arial, Helvetica, sans-serif; color: #ffffff; font-weight: normal; padding-left: 14px; padding-right: 14px; background-color: #337ab7; border-radius: 4px;">

                      <a class="verifybtn" href="$reset_url">Reset Password</a>

                  </td>
                </tr>
              </tbody></table>

                <!--[if (gte mso 9)|(lte ie 8)]><table align="center" border="0" cellspacing="0" cellpadding="0" width="570"><tr><td align="center" valign="top"><![endif]-->
        <div class="oldwebkit" style="max-width: 570px;">
        <table width="570" border="0" cellpadding="0" cellspacing="18" class="vb-container fullpad" bgcolor="#ffffff" style="border-collapse: separate; border-spacing: 18px; padding-left: 0; padding-right: 0; width: 100%; max-width: 570px; background-color: #ffffff;">
          <tbody><tr>
            <td align="left" class="long-text links-color" style="text-align: left; font-size: 13px; font-family: Arial, Helvetica, sans-serif; color: #3f3f3f;">
              <p style="Margin: 1em 0px; Margin-top: 0px;">

                <center>
                or go to this address: <br>
                <a class="linkwrap" href="$reset_url">$reset_url</a>
                </center>
                </p>
            </td>
          </tr>
        </tbody></table>
        </div>
            </td>
          </tr>
        </tbody></table>
        </div>
<!--[if (gte mso 9)|(lte ie 8)]></td></tr></table><![endif]-->
      </td>
    </tr>
  </tbody></table>
  </center>
</body></html>
RESETEMAIL;
