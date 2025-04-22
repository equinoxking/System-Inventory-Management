<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-9" style="text-align: left">
            <h4><strong>REPORTS</strong></h4>
        </div>
        <div class="col-md-3" style="text-align: right">
            <button type="button" class="btn btn-success" id="addReportBtn" title="Add report button">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <table id="reportsTable" class="table-striped table-hover" style="font-size: 12px">
                <thead style="background-color: #3d5c99; color:white">
                    <tr>
                        <th width="12%">Date/Time Submitted</th>
                        <th width="10%">Report Type</th>
                        <th width="10%">Report Number</th>
                        <th width="10%">Submitted By</th>
                        <th width="10%">Position</th>
                        <th width="5%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reports as $report)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($report->created_at)->format('F d, Y H:i A') }}</td>
                        <td>{{ $report->report_type }}</td>
                        <td>{{ $report->control_number }}</td>
                        <td>{{ $report->admin->full_name }}</td>
                        <td>{{ $report->admin->position }}</td>
                        <td class="text-center">
                            <button class="btn btn-info view-pdf-btn" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;" data-filename="{{ $report->report_file }}"><i class="fa fa-eye"></i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="pdfPreviewModal" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen" role="document"> <!-- Use this for Bootstrap 5 -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pdfModalLabel">PDF Preview</h5>
                <button type="button" class="close btn btn-danger" id="pdf-preview-close-btn" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body p-0"> <!-- Remove padding -->
                <iframe id="pdfFrame" src="" width="100%" height="100%" style="border: none;"></iframe>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="addReportModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title" style="color:white;">ADD REPORT FORM</h5>
                    <button type="button" id="add-report-close-btn" data-dismiss="modal" class="btn btn-danger" aria-label="Close">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                </div>
            <div class="modal-body">
                <div class="row">
                <form id="add-report-form" enctype="multipart/form-data">
                    @csrf
                        <div class="form-group">
                            <label for="reportType" class="font-weight-bold">Report Type</label>
                            <select name="report_type" id="report_type" class="form-control">
                                <option value="">Select Report Type</option>
                                <option value="Quarterly">Quarterly Report</option>
                                <option value="Monthly">Monthly Report</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="submittedBy" class="font-weight-bold">Submitted By:</label>
                            <select name="submitted" id="submitted" class="form-control">
                                <option value="">Select Admin</option>
                                @foreach ($admins as $admin)
                                    <option value="{{ $admin->id }}">{{ $admin->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="reportName" class="font-weight-bold">Report File</label>
                            <input type="file" name="pdf" id="pdf_report" class="form-control" accept="application/pdf" >
                        </div>
                    </div>
                    <div class="row">
                        <div class="modal-footer">
                            <div class="col-md-3 form-group">
                                <button type="submit" class="btn btn-success" id="add-report-submit-btn">SUBMIT</button>
                            </div>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('assets/js/admin/report/add-report.js') }}"></script>