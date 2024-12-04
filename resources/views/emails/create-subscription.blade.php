<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Subscription Invitation</title>
      <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
   </head>
   <body>
      <div>
         <div style="font-family:Verdana,sans-serif;background-color:#eef3fb;margin:0;padding:0;padding:30px 0px">
            <div style="max-width:600px;margin:0 auto">
               <table cellspacing="0" cellpadding="0" border="0" width="100%">
                  <tbody>
                     <tr>
                        <td>
                           <div style="color:black;font-size:16px;font-family:Verdana,sans-serif;font-weight:400;text-align:left;line-height:26px">
                              <!-- Personalized greeting -->
                              <p style="color:black;font-size:16px;font-family:Verdana,sans-serif;font-weight:400;text-align:left;line-height:26px">Dear {{ $customer_name }},</p>
                              
                              <!-- Subscription details -->
                              <p style="color:black;font-size:16px;font-family:Verdana,sans-serif;font-weight:400;text-align:left;line-height:26px">
                                 Are you ready to unlock the benefits of the <strong>{{ $plan_name }}</strong> subscription? Click the button below to easily create your subscription and start enjoying the benefits!
                              </p>
                              
                              <p style="color:black;font-size:16px;font-family:Verdana,sans-serif;font-weight:400;text-align:left;line-height:26px">
                                <strong>Plan Name:</strong> {{ $plan_name }}<br>
                                <strong>Plan Price:</strong> ${{ number_format($plan_price, 2) }}<br><br>
                              </p>
                              
                              <!-- Subscription button -->
                              <div style="text-align:center;margin:20px auto;display:block">
                              
    <a href="{{ route('customer.subscribe', [ $plan_code, 'email' => $email]) }}" 
       style="background-color:#0d6efd;border:0;padding:10px;color:#ffffff;display:inline-block;letter-spacing:1px;max-width:300px;min-width:150px;text-align:center;text-decoration:none;text-transform:uppercase;margin:20px auto;font-size:16px;border-radius:5px;font-family:Verdana,sans-serif" 
       target="_blank">
        Subscribe Now
    </a>
</div>
                              </div>

                              <!-- Closing statement -->
                              <p style="color:black;font-size:16px;font-family:Verdana,sans-serif;font-weight:400;text-align:left;line-height:26px">
                                 Yours,<br>
                                 linklink Technologies LLC
                              </p>
                           </div>
                        </td>
                     </tr>
                  </tbody>
               </table>

               <!-- Footer with company logo -->
               <table style="text-align:left;margin:10px auto;width:100%">
                  <tbody>
                     <tr>
                        <td>
                           <center>
                              <div style="text-align:center;margin:0 auto;max-width:100px;display:inline-block;width:100px;height:auto;margin:0 auto;color:#000;background:#fff;text-decoration:none">
                                 <img src="https://i.imgur.com/Qkhoshi.png" style="max-width:100%;width:120px;height:auto;max-height:50px" class="CToWUd" data-bit="iit">
                              </div>
                           </center>
                        </td>
                     </tr>
                  </tbody>
               </table>
               
               <!-- Footer with privacy policy and company address -->
               <table style="text-align:left;margin:10px auto;width:100%">
                  <tbody>
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
                                                   <td style="font-size:12px;color:rgb(149,149,149);font-family:Verdana,sans-serif;font-weight:400;text-align:center;line-height:18px">
                                                      linklink Technologies LLC<br>42 Future Way, Draper, UT 84020
                                                   </td>
                                                </tr>
                                                <tr>
                                                   <td style="font-size:12px;color:rgb(149,149,149);font-family:Verdana,sans-serif;font-weight:400;text-align:center;line-height:18px">
                                                      <a href="#" style="color:#444;font-size:13px;margin:20px auto;display:block" target="_blank">
                                                         Privacy policy and Terms
                                                      </a>
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
