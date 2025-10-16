// resources/js/inventory.js
import Chart from 'chart.js/auto';
document.addEventListener("DOMContentLoaded", () => {
    /** --------------- Edit Modal ---------------- */
    const editModal = document.getElementById("editModal");
    if (editModal) {
        editModal.addEventListener("show.bs.modal", event => {
            const button = event.relatedTarget;
            const id = button.dataset.id;
            const name = button.dataset.name;
            const category = button.dataset.category;
            const quantity = button.dataset.quantity;
            const price = button.dataset.price;
            const unit = button.dataset.unit;

            document.getElementById("edit_item_name").value = name;
            document.getElementById("edit_category_id").value = category;
            document.getElementById("edit_quantity").value = quantity;
            document.getElementById("edit_cost_price").value = price;
            document.getElementById("edit_unit").value = unit;
            document.getElementById("editForm").action = `/inventory/${id}/update`;
        });
    }

    /** --------------- Archive Modal ---------------- */
    const confirmArchiveModal = document.getElementById("confirmArchiveModal");
    if (confirmArchiveModal) {
        confirmArchiveModal.addEventListener("show.bs.modal", event => {
            const button = event.relatedTarget;
            const id = button.dataset.id;
            const name = button.dataset.name;
            document.getElementById("archiveItemName").textContent = name;
            document.getElementById("archiveForm").action = `/inventory/${id}/archive`;
        });
    }

    /** --------------- Stock Status Chart ---------------- */
    const ctxStatus = document.getElementById("stockStatusChart");
    if (ctxStatus) {
        new Chart(ctxStatus.getContext("2d"), {
            type: "doughnut",
            data: {
                labels: ["In Stock", "Low Stock", "Out of Stock"],
                datasets: [
                    {
                        data: window.stockData,
                        backgroundColor: ["#28a745", "#ffc107", "#dc3545"],
                        borderWidth: 1,
                    },
                ],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: "bottom" },
                    title: { display: true, text: "Stock Status Distribution" },
                },
            },
        });
    }

    /** --------------- Category Chart ---------------- */
    const ctxCategory = document.getElementById("itemsByCategoryChart");
    if (ctxCategory) {
        new Chart(ctxCategory.getContext("2d"), {
            type: "bar",
            data: {
                labels: window.categoryLabels,
                datasets: [
                    {
                        label: "Items per Category",
                        data: window.categoryCounts,
                        backgroundColor: "#007bff",
                    },
                ],
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } },
                plugins: {
                    legend: { display: false },
                    title: { display: true, text: "Items by Category" },
                },
            },
        });
    }
});
