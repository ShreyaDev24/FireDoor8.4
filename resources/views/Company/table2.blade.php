<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Document</title>
<style>
body{
  font-family: 'Poppins', sans-serif;
}
.h1,h2,h3,h4,h5,h6,p{
  padding: 0pt;
  margin: 0pt;
}
.border_table table td{
border::1pt solid black;
}
.cusTable{
width:100%;
border-collapse:collapse;
}
.row{
width:100%;
display: flex;
flex-direction:row;
}
.tbl_color{
background:#e6e1dc;
font-weight:bold;
}
.imgClass{
width:100px;
margin-left:8in;
}

.col_left{
  width: 72%;
  float: left;
}

.col_right{
  width: 25%;
  float: right;
}

.dicription_grey{
  background: #ececec;
  font-size: 12px;
  padding-left: 10px;
  width: 50%;
}

.dicription_blank{
  font-size: 12px;
  width: 50%;
  padding-left: 10pt;
}

.table_border_0 table {
    width: 100%;
}

.table_border_0 table td{
  border:0pt;
}
</style>
</head>
<body>
<div>
<div class="col_left border_table">
<h4>Elevation Drawing</h4>
<table class="table table-bordered cusTable">
<tbody>
<tr>
<td rowspan="2">
<img src="{{URL('/')}}/images/logo.png" class="imgClass">
</td>
<td class="tbl_color">Ref</td>
<td colspan="3">DQ018052</td>
<td class="tbl_color">Project</td>
<td>Gulston Road, Coventry</td>
<td class="tbl_color">Prepared By</td>
<td>Richard Maxted</td>
</tr>
<tr>
<td class="tbl_color">Revision</td>
<td>A</td>
<td class="tbl_color">Date</td>
<td>30/07/2020</td>
<td class="tbl_color">Customer</td>
<td>Northstone (NI) Ltd T/A Farrans Construction</td>
<td class="tbl_color">Sales Contact</td>
<td>Gareth Presley</td>
</tr>
</tbody>
</table>
</div>

<div class="col_right border_table">
  <table class="table table-bordered cusTable" style="margin-top: 24px;">
    <tbody>
      <tr>
    <td class="tbl_color" style="font-weight: normal;">SELECT Door Type 01-001 Door</td>
    <td class=""><b>Type 01-001 Door</b></td>
  </tr>
  </tbody>
  </table>
  
    <table class="table table-bordered cusTable">
      <h5>General</h5>
    <tbody>
    <tr>
    <td class="dicription_grey">Ironmongery Set</td>
    <td class="dicription_blank">04</td>
  </tr>
  <tr>
    <td class="dicription_grey">dB Rw Rating (min)</td>
    <td class="dicription_blank">NONE</td>
  </tr>
    <tr>
    <td class="dicription_grey">Fire Rating</td>
    <td class="dicription_blank">FD60S</td>
  </tr>
      <tr>
    <td class="dicription_grey">Swing Type</td>
    <td class="dicription_blank">SS</td>
  </tr>
  <tr>
    <td class="dicription_grey">Additional Notes</td>
    <td class="dicription_blank"></td>
  </tr>
  </tbody>
  </table>

  <table class="table table-bordered cusTable">
      <h5>Door</h5>
    <tbody>
    <tr>
    <td class="dicription_grey">Doorset Range</td>
    <td class="dicription_blank">Centro X</td>
  </tr>
  <tr>
    <td class="dicription_grey">Leaf Facing Pull/Push</td>
    <td class="dicription_blank">Kraft Paper</td>
  </tr>
    <tr>
    <td class="dicription_grey">Leaf Finish Pull/Push</td>
    <td class="dicription_blank">Primed</td>
  </tr>
      <tr>
    <td class="dicription_grey">Lipping Type</td>
    <td class="dicription_blank">None</td>
  </tr>
  <tr>
    <td class="dicription_grey">Lipping Material</td>
    <td class="dicription_blank">Sapele</td>
  </tr>
    <tr>
    <td class="dicription_grey">Leaf Thickness</td>
    <td class="dicription_blank">54</td>
  </tr>
    <tr>
    <td class="dicription_grey">Undercut</td>
    <td class="dicription_blank">18</td>
  </tr>
    <tr>
    <td class="dicription_grey">Vision Panel Glass</td>
    <td class="dicription_blank">NONE</td>
  </tr>
  </tbody>
  </table>

  <table class="table table-bordered cusTable">
      <h5>Frame / Screen</h5>
    <tbody>
    <tr>
    <td class="dicription_grey">Frame Material</td>
    <td class="dicription_blank">Sapele</td>
  </tr>
  <tr>
    <td class="dicription_grey">Frame Type</td>
    <td class="dicription_blank">Lining</td>
  </tr>
    <tr>
    <td class="dicription_grey">Frame - Door Head</td>
    <td class="dicription_blank">796</td>
  </tr>
      <tr>
    <td class="dicription_grey">Frame Door Legs L/R</td>
    <td class="dicription_blank">1206 / 1206</td>
  </tr>
  <tr>
    <td class="dicription_grey">Frame Finish</td>
    <td class="dicription_blank">Primed (Base Coat)</td>
  </tr>
    <tr>
    <td class="dicription_grey">Intumescent Seals To</td>
    <td class="dicription_blank">Frame</td>
  </tr>
    <tr>
    <td class="dicription_grey">Frame Ext. Material</td>
    <td class="dicription_blank"></td>
  </tr>
    <tr>
    <td class="dicription_grey">Frame Ext. Finish</td>
    <td class="dicription_blank"></td>
  </tr>
  <tr>
    <td class="dicription_grey">Frame Ext. Size</td>
    <td class="dicription_blank"></td>
  </tr>
  <tr>
    <td class="dicription_grey">Top Panel Glazing</td>
    <td class="dicription_blank"></td>
  </tr>
  <tr>
    <td class="dicription_grey">Architrave Material</td>
    <td class="dicription_blank">MR MDF - Primed</td>
  </tr>
  <tr>
    <td class="dicription_grey">Architrave Type</td>
    <td class="dicription_blank">Pencil Round</td>
  </tr>
    <tr>
    <td class="dicription_grey">Architrave Size</td>
    <td class="dicription_blank">68x18mm</td>
  </tr>
    <tr>
    <td class="dicription_grey">Architrave Finish</td>
    <td class="dicription_blank">Primed (Base Coat)</td>
  </tr>
    <tr>
    <td class="dicription_grey">Qty Arc. Sets</td>
    <td class="dicription_blank"></td>
  </tr>
    <tr>
    <td class="dicription_grey">Beading</td>
    <td class="dicription_blank">NONE, Pin & Wax</td>
  </tr>
  <tr>
    <td class="dicription_grey">Beading Material</td>
    <td class="dicription_blank">NONE</td>
  </tr>
  <tr>
    <td class="dicription_grey">Bead Finish</td>
    <td class="dicription_blank">NONE</td>
  </tr>
  <tr>
    <td class="dicription_grey">Screen Glass</td>
    <td class="dicription_blank">NONE</td>
  </tr>
  <tr>
    <td class="dicription_grey">Screen Frame Type</td>
    <td class="dicription_blank"></td>
  </tr>
  </tbody>
  </table>
</div>
</div>

<div class="table_border_0" style="clear:both;">
  <table>
    <h3 style="margin-bottom:20px;"><b>Total Doorsets: 28</b></h3>
    <tbody>
      <tr>
        <td>New</td>
        <td>New</td>
        <td>New</td>
        <td>New</td>
        <td>New</td>
        <td>New</td>
        <td>New</td>
        <td>New</td>
        <td>New</td>
        <td>New</td>
        <td>New</td>
        <td>New</td>
        <td>New</td>
        <td>New</td>
        <td>New</td>
        <td>New</td>
      </tr>
      <tr>
        <td>New</td>
        <td>New</td>
        <td>New</td>
        <td>New</td>
        <td>New</td>
        <td>New</td>
        <td>New</td>
        <td>New</td>
        <td>New</td>
        <td>New</td>
      </tr>
    </tbody>
  </table>
</div>

<tfooter>
	<p style="margin-top: 30px;"><b>Copyright © Datim Supplies 2020. Foxwood Industrial Park, Foxwood Road, Chesterfield, Derbyshire, S41 9RN, Tel: 01246 572277, Email: sales@datim.co.uk, Web: www.datim.co.uk</b></p>
</tfooter>
</body>
</html>