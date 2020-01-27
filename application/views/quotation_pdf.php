<!-- <div>
    <h1>Sample</h1>
</div> -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Quotation</title>
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> -->

</head>
<body>
<div class="container">
  <div class="row pt-5 pb-5">
    <div class="col-4"></div>
    <div class="col-4 text-center" style="text-align:center;padding:0% 5%;">
      <img src="<?php echo PATH_LOGO; ?>" class="logoQuotation img-fluid" style="max-width:100px;width:100px;" />
      <h1>QUOTATION</h1>
    </div>
    <div class="col-4"></div>
  </div>


  <table width="100%" class="pt-5 pb-5 mt-5 mb-5" style="padding:0% 5%;">
    <tr>
      <td width="50%">
        <p>Bill to :</p>
        <?php echo $company_name; ?>
        <br>
        <?php echo $company_address; ?>
      </td>
      <td width="50%">
        Date :<br>
        <?php echo date('F j, Y',strtotime($valid_date)); ?>
        <br><br>
        Quotation No :<br>
        <?php echo $quotation_no; ?>   
      </td>
    </tr>
  </table>

  <table width="100%" cellspacing="0" cellpadding="10" class="table lineborder" style="padding:1% 5%;">
    <tr>
      <th style="border-bottom: 1px solid #dadada !important;border-top: 1px solid #dadada !important;padding: 10px 0px;">Products</th>
      <th style="border-bottom: 1px solid #dadada !important;border-top: 1px solid #dadada !important;padding: 10px 0px;">Qty</th>
      <th style="border-bottom: 1px solid #dadada !important;border-top: 1px solid #dadada !important;padding: 10px 0px;">Price</th>
      <th style="border-bottom: 1px solid #dadada !important;border-top: 1px solid #dadada !important;padding: 10px 0px;">Total</th>
    </tr>
    <?php foreach ($products as $key => $value) {
    ?>
    <tr>
      <td style="text-align:left;border-bottom: 0.5px solid #dadada !important;padding: 10px 0px;">
        <img src="<?php echo PATH_IMG; ?><?php echo $value->img ?>" onError="src = '<?php echo PATH_IMG; ?>img_default.png'" class="logoQuotation img-fluid" style="max-width:50px;width:50px;" />
        <br>
        <?php echo $value->name ?> </td>
      <td class="text-right" style="text-align:right;border-bottom: 0.5px solid #dadada !important;padding: 10px 0px;"><?php echo number_format($value->qty); ?>  </td>
      <td class="text-right" style="text-align:right;border-bottom: 0.5px solid #dadada !important;padding: 10px 0px;"><?php echo number_format($value->unit_price); ?>  </td>
      <td class="text-right" style="text-align:right;border-bottom: 0.5px solid #dadada !important;padding: 10px 0px;"><?php echo number_format($value->amount); ?>  </td>
    </tr>


    <?php 
    } ?>

    <tr>
      <td colspan="3" class="text-right" style="border-top: 1px solid #dadada !important;text-align:right"><b>Total Rp </b></td>
      <td class="text-right" style="border-top: 1px solid #dadada !important;padding: 10px 0px;text-align:right"><b><?php echo number_format($total_amount); ?> </b></td>
    </tr>
  </table>


  <ion-grid>
    <ion-row>
      <div style="padding:1% 5%;">
        Submitted by : <br><br>
        <?php echo $fullname; ?><br>
        <?php echo $username; ?><br>
        <?php echo $phone_no; ?>
        </div>
      <ion-col>
      </ion-col>
      <ion-col>

      </ion-col>
    </ion-row>
  </ion-grid>


  <div class="row pt-5 pb-5">
    <div class="col-2"></div>
    <div class="col-8 text-center" style="text-align:center;">
      <br><br>
      <h5 class="footerTitle">PT. Hydromart Utama Indonesia</h5>
      <p class="footerText">
      Sedayu Square Blok H-03<br>
      Jl. Outer Ring Road Lingkar Luar Cengkareng Barat<br>
      RT. 1 , RW. 12 Jakarta Barat 11730<br>
      sales@hydromart.co.id<br>
      021-8062-7055<br>
      <br>
      </p>
    </div>
    <div class="col-2"></div>
  </div>
  


  </div>
</body>
</html>