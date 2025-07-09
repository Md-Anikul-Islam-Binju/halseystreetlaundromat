@extends('admin.app')
@section('admin_content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Halsey Street Laundromat</a></li>
                        <li class="breadcrumb-item active">Expense Section!</li>
                    </ol>
                </div>
                <h4 class="page-title">Expense Section!</h4>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <form action="{{ route('expense.section') }}" method="GET" class="d-flex">
                        <div class="me-2">
                            <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control" placeholder="Start Date">
                        </div>
                        <div class="me-2">
                            <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control" placeholder="End Date">
                        </div>

                        <div class="me-2">
                            <select name="expense_type" class="form-control" >
                                <option value="">All Expense Types</option>
                                <option value="Bill" {{ request('expense_type') == 'Bill' ? 'selected' : '' }}>Bill</option>
                                <option value="Salary" {{ request('expense_type') == 'Salary' ? 'selected' : '' }}>Salary</option>
                                <option value="Damage" {{ request('expense_type') == 'Damage' ? 'selected' : '' }}>Damage</option>
                                <option value="Other" {{ request('expense_type') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </form>
                    <!-- Button to download the PDF report -->
                  <div>
                      <button style="background-color:darkblue;" class="btn text-nowrap text-light"
                              onclick="exportTableToPDF('expense-report.pdf', 'Expense Report')">
                          Download Report
                      </button>
                      <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#addNewModalId">Add New</button>
                  </div>

                </div>
            </div>
            <div class="card-body">
                <table  class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Title</th>
                        <th>Expense Type</th>
                        <th>Amount</th>
                        <th>Expense Date</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($expense as $key=>$expenseData)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>
                                {{$expenseData->title}}
                            </td>
                            <td>
                                {{$expenseData->expense_type}}
                            </td>
                            <td>
                                {{$expenseData->amount}}
                            </td>
                            <td>
                                {{$expenseData->date}}
                            </td>
                            <td style="width: 100px;">
                                <div class="d-flex justify-content-end gap-1">
                                     <a href="{{route('expense.destroy',$expenseData->id)}}"class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#danger-header-modal{{$expenseData->id}}">Delete</a>
                                </div>
                            </td>
                        </tr>


                        <div id="danger-header-modal{{$expenseData->id}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="danger-header-modalLabel{{$expenseData->id}}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header modal-colored-header bg-danger">
                                        <h4 class="modal-title" id="danger-header-modalLabe{{$expenseData->id}}l">Delete</h4>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <h5 class="mt-0">Are You Went to Delete this ? </h5>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                        <a href="{{route('expense.destroy',$expenseData->id)}}" class="btn btn-danger">Delete</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                    </tbody>
                </table>
                <div class="d-flex justify-content-end">
                    <strong>Total Amount: </strong> <span>{{ number_format($totalAmount, 2) }}</span>
                </div>

            </div>
        </div>
    </div>

    <!--Add Modal -->
    <div class="modal fade" id="addNewModalId" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addNewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addNewModalLabel">Add</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{route('expense.store')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Title</label>
                                    <input type="text" id="title" name="title"
                                           class="form-control" placeholder="Enter Title" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="example-select" class="form-label">Expense Type</label>
                                    <select class="form-select" id="example-select" name="expense_type" required>
                                        <option value="">Select Expense Type</option>
                                        <option value="Bill">Bill</option>
                                        <option value="Salary">Salary</option>
                                        <option value="Damage">Damage</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Amount</label>
                                    <input type="number" id="amount" name="amount"
                                           class="form-control" placeholder="Enter Amount" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="example-date-input" class="form-label">Expense Date</label>
                                    <input class="form-control" type="date"  id="example-date-input" name="date" required>
                                </div>
                            </div>


                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="example-fileinput" class="form-label">File</label>
                                    <input type="file" name="image" id="example-fileinput" class="form-control" >
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label> Details </label>
                                        <textarea class="form-control" id="content" name="details" placeholder="Enter the Description"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>
    <script>
        function exportTableToPDF(filename, heading) {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            doc.text(heading, doc.internal.pageSize.getWidth() / 2, 20, { align: 'center' });
            let rows = document.querySelectorAll("table tr");
            let data = [];
            for (let i = 0; i < rows.length; i++) {
                let row = [],
                    cols = rows[i].querySelectorAll("td, th");
                for (let j = 0; j < cols.length - 1; j++)
                    row.push(cols[j].innerText);
                data.push(row);
            }
            doc.autoTable({
                head: [data[0]],
                body: data.slice(1),
                startY: 30
            });
            doc.save(filename);
        }
    </script>
@endsection
