<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Company Info Details</title>
      <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
      <style>
         body {
   font-family: 'Arial', sans-serif;
   font-size: 16px;
   color: #000 !important; /* Ensures text color is black */
}
p, li {
   color: #000 !important; /* Forces black color for all elements */
}
a {
    color: #007bff;
   text-decoration: none;
}
a:hover {
   text-decoration: underline;
}

      </style>
   </head>
   <body>
      <div style="background-color:#eef3fb;padding:30px 0px">
         <div style="max-width:600px;margin:0 auto;background:#fff;padding:20px;border-radius:8px">
            <table width="100%" cellpadding="0" cellspacing="0">
               <tr>
                  <td>
                     <p>Dear {{ $data['customer_name'] }},</p>
                     <p><strong>Company Info Details</strong></p>
                     <p>Test User have sent the Company Info details</p>
                     <p>Here are your company details:</p>
                     <ul>
                        <li>Provider Data CSV Link:
                           @if($data['csv_link'] !== 'N/A')
                              <a href="{{ $data['csv_link'] }}" download>Download CSV</a>
                           @else
                              Not Available
                           @endif
                        </li>
                        <li>Company Info Logo link:
                           @if($data['logo_link'] !== 'N/A')
                              <a href="{{ $data['logo_link'] }}" target="_blank">
                                 <img src="{{ $data['logo_link'] }}" alt="Company Logo" style="max-width:150px;display:block;margin-top:5px;">
                              </a>
                           @else
                              Not Available
                           @endif
                        </li>
                        <li>Company Info Company Name: {{ $data['company_name'] }}</li>
                        <li>Company Info Landing Page URL: 
                           @if($data['landing_url'] !== 'N/A')
                           <a href="{{ $data['landing_url'] }}" target="_blank">Visit Website</a>
                           @else
                              Not Available
                           @endif
                        </li>
                     </ul>
                     <p>Yours,<br>Clearlink Technologies LLC</p>
                  </td>
               </tr>
            </table>
         </div>
      </div>
   </body>
</html>
