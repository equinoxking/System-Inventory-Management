@extends('admin.layout.admin-layout')
@section('content')
<div style="display: flex; justify-content: center; align-items: center; height: 100vh;">
    <div style="background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); width: 50%;">
        <h3>Request Form</h3>
        <form id="requestForm" style="display: flex; flex-direction: column; gap: 10px;">
            <div style="display: flex; align-items: center;">
                <label for="date" style="width: 30%;"><strong>Date:</strong></label>
                <input type="text" id="date" value="{{ date('Y-m-d') }}" disabled style="width: 70%; padding: 5px;">
            </div>
            
            <div style="display: flex; align-items: center;">
                <label for="requester" style="width: 30%;"><strong>Requester:</strong></label>
                <input type="text" id="requester" value="John Doe" disabled style="width: 70%; padding: 5px;">
            </div>
            
            <div style="display: flex; align-items: center;">
                <label for="category" style="width: 30%;"><strong>Category:</strong></label>
                <select id="category" onchange="updateItems()" style="width: 70%; padding: 5px;">
                    <option value="">Select Category</option>
                    <option value="Office Supplies">Office Supplies</option>
                    <option value="Electronics">Electronics</option>
                    <option value="Furniture">Furniture</option>
                </select>
            </div>
            
            <div style="display: flex; align-items: center;">
                <label for="item" style="width: 30%;"><strong>Item:</strong></label>
                <select id="item" onchange="updateStock()" style="width: 60%; padding: 5px;">
                    <option value="">Select Item</option>
                </select>
                <input type="text" id="stock" disabled style="width: 10%; padding: 5px; text-align: center; margin-left: 5px;">
            </div>
            
            <div style="display: flex; align-items: center;">
                <label for="quantity" style="width: 30%;"><strong>Quantity:</strong></label>
                <input type="number" id="quantity" oninput="updateAvailableStock()" min="0" style="width: 70%; padding: 5px;">
            </div>
            
            <div style="display: flex; align-items: center;">
                <label for="unit" style="width: 30%;"><strong>Unit:</strong></label>
                <input type="text" id="unit" placeholder="e.g., pieces, boxes" style="width: 70%; padding: 5px;">
            </div>
            
            <div style="display: flex; align-items: center;">
                <label for="time" style="width: 30%;"><strong>Time:</strong></label>
                <input type="text" id="time" disabled style="width: 70%; padding: 5px;">
            </div>
            
            <button type="button" onclick="submitRequest()" style="padding: 10px; background: #2f4a7f; color: white; border: none; border-radius: 5px; cursor: pointer;">Submit</button>
        </form>
    </div>
</div>

<script>
    const stockData = {
        "Pen": 50, "Notebook": 30, "Stapler": 20, "Paper": 100, "Envelopes": 40,
        "Laptop": 5, "Mouse": 25, "Keyboard": 15, "Monitor": 10, "Printer": 8,
        "Chair": 12, "Table": 7, "Cabinet": 6, "Bookshelf": 9, "Sofa": 3
    };
    
    function updateItems() {
        const category = document.getElementById("category").value;
        const itemDropdown = document.getElementById("item");
        itemDropdown.innerHTML = "";
        
        const items = {
            "Office Supplies": ["Pen", "Notebook", "Stapler", "Paper", "Envelopes"],
            "Electronics": ["Laptop", "Mouse", "Keyboard", "Monitor", "Printer"],
            "Furniture": ["Chair", "Table", "Cabinet", "Bookshelf", "Sofa"]
        };
        
        if (items[category]) {
            items[category].forEach(item => {
                let option = document.createElement("option");
                option.value = item;
                option.textContent = item;
                itemDropdown.appendChild(option);
            });
            
            if (items[category].length > 0) {
                itemDropdown.value = items[category][0];
                updateStock();
            }
        }
    }
    
    function updateStock() {
        const selectedItem = document.getElementById("item").value;
        document.getElementById("stock").value = stockData[selectedItem] || "N/A";
    }
    
    function updateAvailableStock() {
        const selectedItem = document.getElementById("item").value;
        let quantity = parseInt(document.getElementById("quantity").value) || 0;
        
        if (quantity < 0) {
            document.getElementById("quantity").value = 0;
            quantity = 0;
        }
        
        if (stockData[selectedItem] !== undefined) {
            let newStock = stockData[selectedItem] - quantity;
            document.getElementById("stock").value = newStock >= 0 ? newStock : "Out of Stock";
        }
    }
    
    function updateTime() {
        document.getElementById("time").value = new Date().toLocaleTimeString();
    }
    
    setInterval(updateTime, 1000);
    
    function submitRequest() {
        Swal.fire({
            title: "Request Submitted!",
            text: "Your request has been successfully submitted.",
            icon: "success"
        });
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
