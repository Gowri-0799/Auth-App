@extends("layouts.admin")
@section('title', "Provider Data")

@section('content')
<div id="content" style="box-sizing: border-box; margin-left:300px; width:80%" class="p-3">
<div class="col-md-12">

<div class="mb-2 w-100">
    <h2 class="mt-2 mb-5">Provider Availability Info</h2>
</div>
<p class="mb-4">Your availability information dictates the zip codes in which you’re available</p>
<ol class="mb-4 p-0 ps-3">
        <li class="numbered">Download this <span><a href="/assets/sample/zip_list_template.csv" class="text-decoration-underline">zip_list_template.csv</a></span> </li>
        <li class="numbered">
            <div class="tableTerms p-0 border-0">
                <div class="accordion accordion-flush" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button provider-accordion-button collapsed m-0 p-0  text-danger" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                <span class="text-dark button-text">Add your availability to the zip list template.</span>&nbsp;Important info for the CSV file <span class="ms-1"><i class="fa-solid fa-angle-down"></i></span>
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <ul id="billing">
                                    <li class="billing">
                                        File name can be changed. Column names and order <strong>should not</strong> be changed.
                                    </li>
                                    <li class="billing">
                                        Rows must be unique based on the combination of ZIP, Type and CustomerType.
                                        <ul>
                                            <li class="provider">A file <strong>may</strong> contain multiple rows with the same ZIP if each row has a unique Type and/or CustomerType. For example, the following is acceptable.
                                                <ul>
                                                    <li class="data">“00544, 10, Fiber, .99, Residential”</li>
                                                    <li class="data">“00544, 10, Fiber, .99, Business”</li>
                                                </ul>
                                            </li>
                                        </ul>
                                        <ul>
                                            <li class="provider">A file <strong>may not</strong> contain multiple rows with the same ZIP if the Type and/or CustomerType is not unique. For example the following <strong>is not</strong> acceptable.
                                                <ul>
                                                    <li class="data">“00544, 10, Fiber, .99, Residential”</li>
                                                    <li class="data">“00544, 5, Fiber, .74, Residential”</li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="billing">
                                        <strong>ZIP:&nbsp;</strong> Include 5 digit zip code.
                                        <ul>
                                            <li class="provider">Zips with preceding zeros can be added with or without the zeros. For example, 00544 zip code can be entered as 544.</li>
                                        </ul>
                                    </li>
                                    <li class="billing">
                                        <strong>Speed:&nbsp;</strong> Represents the download speed maximum available in that zip code. This number should be in Mbps when uploaded.
                                    </li>
                                    <li class="billing">
                                        <strong>Type:&nbsp;</strong> The technology type associated with service in the zip code. Please use the following options: 5G Home, Cable, DSL, Fiber, Fixed Wireless, LTE Home, Mobile, Other Copper Wireline, Satellite
                                    </li>
                                    <li class="billing"> <strong>Coverage:&nbsp;</strong> Percentage of the zip code area covered by the service. Use the decimal representation. For example, 100% would be 1 and 74% would be 0.74.</li>
                                    <li class="billing"> <strong>CustomerType:&nbsp;</strong> Business or Residential </li>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </li>
        <li class="numbered">Upload the completed CSV file</li>
    </ol>
    <p id="errorText" class="text-danger"></p>
    <form id="csvForm" action="/upload-csv" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="mkq8jDkIsA930wy5gEss1ouLXgewMdOMAFuM90Pc" autocomplete="off">        <div class="row">
            <div class="mb-4 col-lg-6">
                <input type="file" class="form-control" name="csv_file" accept=".csv" id="csvFileInput">
            </div>
        </div>
        <div>
            <button type="button" class="btn btn-primary text-center text-white px-3 mb-4 mt-3" id="openModal">Upload availability info</button>
        </div>
    </form>
    <hr class="borders-clearlink">
    <h4 class="fw-bold mt-5">Search previous file history</h4>
    <div class="top-row w-100 mt-4">
        <div class="row ">
            <div class="col-md-11">
                <form action="" method="GET" class="row g-3 align-items-center w-100">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="fw-bold">Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="" />
                        </div>
                        <div class="col-md-3">
                            <label class="fw-bold">End Date</label>
                            <input type="date"  name="end_date" class="form-control" value="" />
                        </div>
                        <div class="col-md-2">
                            <label for="per_page" class="fw-bold">Show:</label>
                            <select name="per_page" id="per_page" class="form-select">
                                <option value="10" >10</option>
                                <option value="25" >25</option>
                                <option value="50" >50</option>
                                <option value="100" >100</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="search" class="fw-bold">Search</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="search" name="search" value="">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="input-group mt-4">
                                <button class="btn button-clearlink text-primary fw-bold" type="submit">Submit</button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            </div>
        <div class="row">
            <form action="/provider-info" method="GET" class="row align-items-center w-100">
                <div class="col-md-1">
                    <div class="input-group">
                        <button class="btn text-primary text-decoration-underline fw-bold p-0 pt-2" type="submit">Reset</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>File Name</th>
                <th>File Size</th>
                <th>ZIP Count</th>
                <th>CSV File URL</th>
            </tr>
        </thead>
        <tbody>
                        <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    <a class="btn btn-primary btn-sm" href="">Download</a>

                </td>
            </tr>
                    </tbody>
    </table>
    <div class="mt-2 mb-5 paginate">
        <div class="row">
            <div class="col-lg mt-4">
                Total Count: <strong>1</strong>
            </div>

            <div class="pagination col-lg m-0">
                            </div>
        </div>


    </div>
</div>
</div>
@endsection