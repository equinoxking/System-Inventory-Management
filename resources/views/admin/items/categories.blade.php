@extends('admin.layout.admin-layout')
@section('content')
<div class="container-fluid mt-3 mb-3">
    <div class="row align-items-center">
        <div class="col-md-12">
            <!-- Breadcrumb Navigation -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Lookup Tables</li>
                    <li class="breadcrumb-item active" aria-current="page">Categories</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-9" style="text-align: left">
            <h4><strong>CATEGORIES</strong></h4>
        </div>
        <div class="col-md-3" style="text-align: right">
            <button type="button" class="btn btn-success" id="addCategoryBtn" title="Add category button">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <table id="categoryTable" class="table-hover" style="font-size: 11px">
                <thead>
                    <th width="7%">Category Number</th>
                    <th width="20%">Category Name</th>
                    <th width="23%">Description</th>
                    <th width="10%">Date/Time Created</th>
                    <th width="5%" class="text-center">Action</th>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td>{{ $category->control_number }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->description }}</td>
                            <td>{{ \Carbon\Carbon::parse($category->created_at)->format('F d, Y H:i A') }}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-warning" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;" onclick="editCategory('{{ addslashes(json_encode($category)) }}')" title="Edit category button"><i class="fa fa-edit" style="color: white;"></i></button>
                                <button type="button" class="btn btn-danger" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;" onclick="deleteCategory('{{ addslashes(json_encode($category)) }}')" title="Delete category button"><i class="fa fa-trash" style="color: white;"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" style="color:white;">EDIT CATEGORY FORM</h5>
                    <button type="button" id="edit-category-close-btn" data-dismiss="modal" class="btn bg-danger" aria-label="Close">
                        <i class="fa-solid fa-circle-xmark" style="color: white;"></i>
                    </button>
                </div>
            <div class="modal-body">
                <div class="row">
                    <form id="edit-category-form">
                        @csrf
                        <div class="form-group" hidden>
                            <label for="category_id">Category ID</label>
                            <input type="text" class="form-control" name="category_id" id="edit-category-id">
                        </div>
                        <div class="form-group">
                            <label for="category_control_number">Category ID</label>
                            <input type="text" class="form-control" name="category_control_number" id="edit-category-control_number" readonly>
                        </div>
                        <div class="form-group">
                            <select name="main_category" id="main_category" class="form-control">
                                <option value="">Select Main Category</option>
                                @foreach ($sub_categories as $sub_category)
                                    <option value="{{ $sub_category->id }}">{{ $sub_category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="category_name">Category Name</label>
                            <input type="text" class="form-control" name="category_name" id="edit-category-name">
                        </div>
                        <div class="form-group">
                            <label for="category_description">Description</label>
                            <textarea type="text" class="form-control" name="category_description" id="edit-category-description" cols="5" rows="5" placeholder="This is Optional"></textarea>
                        </div>
                </div>
                <div class="row">
                    <div class="modal-footer">
                        <div class="form-group">
                            <button type="submit" class="btn btn-warning" id="edit-category-submit-btn">SAVE</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="deleteCategoryModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" style="color:white;">DELETE CATEGORY FORM</h5>
                </div>
            <div class="modal-body">
                <div class="row">
                <form id="delete-category-form">
                        <div class="col-md-3 form-group" hidden>
                            <label for="deleteCategoryId">category ID</label>
                            <input type="text" class="form-control" name="category_id" id="delete-category-id">
                        </div>
                        <strong>Are you sure you want to delete this category?</strong>
                </div>
                <div class="row">
                    <div class="modal-footer d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-success" id="delete-category-close-btn">NO</button>
                        <button type="submit" class="btn btn-danger" id="delete-category-submit-btn">YES</button>
                    </div>   
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title" style="color:white;">ADD CATEGORY FORM</h5>
                    <button type="button" id="add-category-close-btn" data-dismiss="modal" class="btn btn-danger" aria-label="Close">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                </div>
            <div class="modal-body">
                <div class="row">
                <form id="add-category-form">
                    @csrf
                        <div class="form-group">
                            <label for="addMainCategory" class="font-weight-bold">Main Category</label>
                            <select name="main_category" id="add-main-category" class="form-control">
                                <option value="">Select Main Category</option>
                                @foreach ($sub_categories as $sub_category)
                                    <option value="{{ $sub_category->id }}">{{ $sub_category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="reportName" class="font-weight-bold">Category Name</label>
                            <input type="text" class="form-control" name="category_name" id="add-category-name">
                        </div>
                        <div class="form-group">
                            <label for="reportName" class="font-weight-bold">Description</label>
                            <textarea type="text" class="form-control" name="category_description" id="add-category-description" placeholder="This is optional" cols="5" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="modal-footer">
                            <div class="form-group">
                                <button type="submit" class="btn btn-success" id="add-category-submit-btn">SAVE</button>
                            </div>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('assets/js/admin/items/category-functions/edit-category.js') }}"></script>
<script src="{{ asset('assets/js/admin/items/category-functions/delete-category.js') }}"></script>
<script src="{{ asset('assets/js/admin/items/category-functions/add-category.js') }}"></script>