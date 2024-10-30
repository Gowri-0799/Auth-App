<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">    
</head>
<body>

<div>
         <div style="font-family:Verdana,sans-serif;background-color:#eef3fb;margin:0;padding:0;padding:30px 0px">
            <div style="max-width:600px;margin:0 auto">
               <table cellspacing="0" cellpadding="0" border="0" width="100%">
                  <tbody>
                     <tr>
                        <td style="background-color:white;padding:30px 0px">
                           <table style="text-align:center;margin:10px auto;width:100%">
                              <tbody>
                                 <tr>
                                    <td>
                                       <div style="max-width:600px;margin:0 auto;padding:30px 50px">
                                          <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                             <tbody>
                                                <tr>
                                                   <td>
                                                      <div style="color:black;font-size:16px;font-family:Verdana,sans-serif;font-weight:400;text-align:left;line-height:26px">
                                                         <p style="color:black;font-size:16px;font-family:Verdana,sans-serif;font-weight:400;text-align:left;line-height:26px">Dear {{ $userName }},</p>
                                                         <p style="color:black;font-size:16px;font-family:Verdana,sans-serif;font-weight:400;text-align:left;line-height:26px">We heard that you lost your password. Sorry about that! <br> But don’t worry! You can use the following button to reset your password:</p>
                                                         <div style="text-align:center;margin:20px auto;display:block"><a style="background-color:#0d6efd;border:0;padding:10px;color:#ffffff;display:inline-block;letter-spacing:1px;max-width:300px;min-width:150px;text-align:center;text-decoration:none;text-transform:uppercase;margin:20px auto;font-size:16px;border-radius:5px;font-family:Verdana,sans-serif" href="{{ $resetUrl }}" target="_blank">Reset Password</a>
                                                         </div>
                                                      </div>
                                                   </td>
                                                </tr>
                                             </tbody>
                                          </table>
                                       </div>
                                    </td>
                                 </tr>
                              </tbody>
                           </table>
                           <table style="text-align:left;margin:10px auto;width:100%">
                              <tbody>
                                 <tr>
                                    <td style="padding:0px 50px 50px 50px">
                                       <p style="margin-bottom:5px;font-size:15px;color:black!important">Yours,</p>
                                       <p style="margin-top:5px;font-size:15px;color:black!important">Testlink Technologies LLC</p>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>
                                       <center>
                                          <div style="text-align:center;margin:0 auto;max-width:100px;display:inline-block;width:100px;height:auto;margin:0 auto;color:#000;background:#fff;text-decoration:none">
                                              <img src="https://i.imgur.com/Qkhoshi.png" alt="Testlink Logo" style="max-width:100%;width:120px;height:auto;max-height:50px">
                                          </div>
                                       </center>
                                    </td>
                                 </tr>
                              </tbody>
                           </table>
                        </td>
                     </tr>
                     <tr>
                  <td style="background-color:#eef3fb">
                     <div style="max-width:600px;margin:0 auto;padding:10px 0;text-align:center">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color:#eef3fb">
                           <tbody>
                              <tr>
                                 <td align="center" style="padding:10px 0 10px 0">
                                    <table cellspacing="0" cellpadding="0" border="0" width="600">
                                       <tbody>
                                          <tr>
                                             <td style="font-size:12px;color:rgb(149,149,149);font-family:Verdana,sans-serif;font-weight:400;text-align:center;line-height:18px">Testlink Technologies LLC<br>42 Past Way, Wraper, AT 04020
                                             </td>
                                          </tr>
                                          <tr>
                                             <td style="font-size:12px;color:rgb(149,149,149);font-family:Verdana,sans-serif;font-weight:400;text-align:center;line-height:18px"><a href="#" style="color:#444;font-size:13px;margin:20px auto;display:block" target="_blank"><span style="color:#444;font-size:13px">Privacy policy and Terms</span></a>
                                             </td>
                                          </tr>
                                       </tbody>
                                    </table>
                                 </td>
                              </tr>
                           </tbody>
                        </table>
                     </div>
                  </td>
               </tr>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
</body>
</html>