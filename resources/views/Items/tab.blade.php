@extends("layouts.Master")

@section("main_section")
<div class="app-main__outer">
<div class="app-main__inner">
<div class="col-lg-12">
<div class="card">
<div class="card-body">
<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
<li class="nav-item tab-item">
<a class="nav-link show active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Home</a>
</li>
<li class="nav-item tab-item">
<a class="nav-link show" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false" style="
">Profile</a>
</li>
<li class="nav-item tab-item">
<a class="nav-link show" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Contact</a>
</li>
</ul>
<div class="tab-content" id="pills-tabContent">
<div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
<p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nostrum ratione officia libero maiores, explicabo cumque dolorem quasi rerum molestiae. Ex ut tempora odit voluptatem, libero culpa nostrum dolores enim velit magnam repellendus! Porro repudiandae mollitia odit eveniet molestias consequuntur deleniti quisquam ducimus quidem autem? Error culpa nostrum, nemo quo quisquam illo architecto id nihil pariatur esse recusandae alias quaerat voluptates iure consequuntur repellat cupiditate perferendis iste praesentium. Suscipit, molestias consequatur.</p>
<div class="col-lg-12 mb-4">
<div class="card">
<div class="card-body">
<h4 class="header-title">Thead danger</h4>
<div class="single-table">
<div class="table-responsive">
<table class="table text-center">
<thead class="text-uppercase bg-danger">
<tr class="text-white">
<th scope="col">ID</th>
<th scope="col">Name</th>
<th scope="col">date</th>
<th scope="col">price</th>
<th scope="col">action</th>
</tr>
</thead>
<tbody>
<tr>
<th scope="row">1</th>
<td>Mark</td>
<td>09 / 07 / 2018</td>
<td>$120</td>
<td><i class="ti-trash"></i></td>
</tr>
<tr>
<th scope="row">1</th>
<td>jone</td>
<td>09 / 07 / 2018</td>
<td>$150</td>
<td><i class="ti-trash"></i></td>
</tr>
<tr>
<th scope="row">1</th>
<td>Mark</td>
<td>09 / 07 / 2018</td>
<td>$120</td>
<td><i class="ti-trash"></i></td>
 </tr>
<tr>
<th scope="row">1</th>
<td>jone</td>
<td>09 / 07 / 2018</td>
<td>$150</td>
<td><i class="ti-trash"></i></td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="tab-pane fade active show" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
<p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nostrum ratione officia libero maiores, explicabo cumque dolorem quasi rerum molestiae. Ex ut tempora odit voluptatem, libero culpa nostrum dolores enim velit magnam repellendus! Porro repudiandae mollitia odit eveniet molestias consequuntur deleniti quisquam ducimus quidem autem? Error culpa nostrum, nemo quo quisquam illo architecto id nihil pariatur esse recusandae alias quaerat voluptates iure consequuntur repellat cupiditate perferendis iste praesentium. Suscipit, molestias consequatur.</p>
</div>
<div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
<div class="form-row">
<div class="col-md-6">
<div class="position-relative form-group"><label for="Company Name" class="">Company Name</label>
<input name="CompanyName" required="" placeholder="Enter Company Name" value="" type="text" class="form-control"></div>
</div>
<div class="col-md-6">
<div class="position-relative form-group">
<label for="Website url" class="">Website</label>
<input name="CompanyWebsite" value="" required="" placeholder="Enter Company Website" type="url" class="form-control">
</div>
</div>

<div class="col-md-6">
<div class="position-relative form-group">
<label for="Company Email" class="">Company Email</label>
<input name="CompanyEmail" value="" required="" placeholder="Enter Company Email" type="email" class="form-control">
</div>
</div>

<div class="col-md-6">
<div class="position-relative form-group"><label for="Company Phone" class="">Company Phone</label>
<input name="CompanyPhone" value="" required="" placeholder="Enter Company Phone Number" type="number" class="form-control"></div>
</div>
<div class="col-md-6">
<div class="position-relative form-group">
<label for="Company VAT" class="">Company VAT</label>
<input name="CompanyVatNumber" value="" required="" placeholder="Enter Company Company VAT" type="text" class="form-control">
</div>
</div>

<div class="col-md-6">
<div class="position-relative form-group">
<label for="Company Logo" class="">Company Logo</label>
<input name="CompanyLogo" required="" type="file" class="form-control">
</div>
</div>

<div class="col-md-12">
<div class="position-relative form-group">
<label for="Company Moreinfo" class="">Company Moreinfo</label>
<textarea name="CompanyMoreInfo" required="" placeholder="Enter Company Moreinfo" class="form-control"></textarea>
</div>
</div>
</div>
<div class="tab-pane fade active show" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
<p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nostrum ratione officia libero maiores, explicabo cumque dolorem quasi rerum molestiae. Ex ut tempora odit voluptatem, libero culpa nostrum dolores enim velit magnam repellendus! Porro repudiandae mollitia odit eveniet molestias consequuntur deleniti quisquam ducimus quidem autem? Error culpa nostrum, nemo quo quisquam illo architecto id nihil pariatur esse recusandae alias quaerat voluptates iure consequuntur repellat cupiditate perferendis iste praesentium. Suscipit, molestias consequatur.</p>
</div>
</div>

</div>
</div>
</div>
</div>
</div>
</div>
@endsection