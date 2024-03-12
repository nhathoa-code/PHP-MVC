function slug(str) {
  str = str.toLowerCase();
  str = str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
  str = str.replace(/[đĐ]/g, "d");
  str = str.replace(/([^0-9a-z-\s])/g, "");
  str = str.replace(/(\s+)/g, "-");
  str = str.replace(/-+/g, "-");
  str = str.replace(/^-+|-+$/g, "");
  return str;
}

async function urlToBlob(url) {
  const response = await fetch(url);
  const blob = await response.blob();
  const urlParts = url.split("/");
  const filenameFromURL = urlParts[urlParts.length - 1];
  return new File([blob], filenameFromURL, { type: blob.type });
}

function urlsToFiles(urls) {
  const promises = urls.map((url) => urlToBlob(url));
  return Promise.all(promises);
}

async function imageUrlToFile(imageUrl) {
  try {
    const response = await fetch(imageUrl);
    const blob = await response.blob();
    const urlParts = imageUrl.split("/");
    const filename = urlParts[urlParts.length - 1];
    return new File([blob], filename, { type: blob.type });
  } catch (error) {
    console.error("Error converting image URL to file:", error);
    throw error;
  }
}

$(document).on("click", ".toggle-variation", function () {
  let p_id = $(this).data("p_id");
  let product = variation_of_products.find((item) => item.p_id === p_id);
  if (product) {
    let variation_name = "";
    let variation_price = "";
    let variation_stock = "";
    if ($(this).hasClass("more")) {
      for (let item in product.variation) {
        product.variation[item].forEach((item) => {
          variation_name += `<div>${item.color_name},${item.size}</div>`;
          variation_price += `<div>${
            new Intl.NumberFormat({ style: "currency" }).format(item.price) +
            "₫"
          }</div>`;
          variation_stock += `<div>${item.stock}</div>`;
        });
      }
      variation_name += `<div class="toggle-variation less" data-p_id="${product.p_id}">Ẩn bớt<div>`;
    } else {
      let first_color = Object.values(product.variation)[0];
      first_color.forEach((item) => {
        variation_name += `<div>${item.color_name},${item.size}</div>`;
        variation_price += `<div>${
          new Intl.NumberFormat({ style: "currency" }).format(item.price) + "₫"
        }</div>`;
        variation_stock += `<div>${item.stock}</div>`;
      });
      variation_name += `<div class="toggle-variation more" data-p_id="${product.p_id}">Xem thêm<div>`;
    }

    $(`#product-table tbody tr#${product.p_id} td.product-variation-name`).html(
      variation_name
    );
    $(
      `#product-table tbody tr#${product.p_id} td.product-variation-price`
    ).html(variation_price);
    $(
      `#product-table tbody tr#${product.p_id} td.product-variation-stock`
    ).html(variation_stock);
  }
});

$("#add-cat-form").submit(function (event) {
  event.preventDefault();
  const this2 = this;
  $(this).find("button").prop("disabled", true);
  $(this).find("button").css({
    cursor: "not-allowed",
  });
  $(this).find("button").after(loading_icon);
  const formData = new FormData(this);
  const level = $("#add-cat-form select").find("option:selected").data("level");
  $.ajax({
    type: "POST",
    url: $(this).attr("action"),
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      let added_item = response.added_item;
      let added_item_string = `<tr id="cat-${added_item.id}">
                                    <td class="name">
                                        ${"— ".repeat(level + 1)} ${
        added_item.cat_name
      }
                                        <div class="row-actions">
                                            <span data-route="${
                                              added_item.delete_url
                                            }" class="delete"><a href="javascript:void(0)">Delete</a></span>  | 
                                            <span class="edit"><a href="${
                                              added_item.edit_url
                                            }">Edit</a></span>
                                        </div>
                                    </th>
                                    <td>
                                        ${
                                          added_item.hasOwnProperty(
                                            "cat_picture"
                                          )
                                            ? `<img style="width: 50px;" src="${added_item.cat_picture}" alt="">`
                                            : `<div style="width:50px;height:50px"></div>`
                                        }
                                        
                                    </td>
                                    <td>${added_item.cat_slug}</td>
                                    <td>1</td>
                                </tr>`;
      if (added_item.hasOwnProperty("parent_id")) {
        $(`#cat-table tbody tr#cat-${added_item.parent_id}`).after(
          added_item_string
        );
        $(
          `#add-cat-form select option#parent-id-${added_item.parent_id}`
        ).after(
          `<option id="parent-id-${added_item.id}" data-level="${
            level + 1
          }" value="${added_item.id}">${"— ".repeat(level + 1)} ${
            added_item.cat_name
          }</option>`
        );
      } else {
        $("#cat-table tbody").prepend(added_item_string);
        $("#add-cat-form select option")
          .first()
          .after(
            `<option id="parent-id-${added_item.id}" data-level="0" value="${added_item.id}">${added_item.cat_name}</option>`
          );
      }
      $(this2).find("button").prop("disabled", false);
      $(this2).find("button").css({
        cursor: "default",
      });
      $(this2).find("button").next("svg").remove();
      $(this2).trigger("reset");
      $(".preview-image").attr("src", null).hide();
    },
    error: function (xhr, status, error) {
      notif({
        msg: xhr.responseJSON.message,
        type: "warning",
        position: "center",
        height: "auto",
        top: 80,
        timeout: 5000,
        animation: "slide",
      });
      $(this2).find("button").prop("disabled", false);
      $(this2).find("button").css({
        cursor: "pointer",
      });
      $(this2).find("button").next("svg").remove();
    },
  });
});

$("#update-cat-form").submit(function (event) {
  event.preventDefault();
  const this2 = this;
  $(this).find("button").prop("disabled", true);
  $(this).find("button").css({
    cursor: "not-allowed",
  });
  $(this).find("button").after(loading_icon);
  const formData = new FormData(this);
  $.ajax({
    type: "POST",
    url: $(this).attr("action"),
    data: formData,
    processData: false,
    contentType: false,
    success: function (res) {
      window.location.href = res.category_url;
    },
    error: function (xhr, status, error) {
      notif({
        msg: xhr.responseJSON.message,
        type: "warning",
        position: "center",
        height: "auto",
        top: 80,
        timeout: 5000,
        animation: "slide",
      });
      $(this2).find("button").prop("disabled", false);
      $(this2).find("button").css({
        cursor: "pointer",
      });
      $(this2).find("button").next("svg").remove();
    },
  });
});

$("#add-cat-form input[name=cat_name]").on("input", function () {
  $("#add-cat-form input[name=cat_slug]").val(slug($(this).val()));
});

$(".image-upload-container").on("click", function () {
  $("#file-input").trigger("click");
  $("#files-input").trigger("click");
});

const allowed_types = ["image/jpeg", "image/png"];
var uploadedFile = null;
$("#file-input").on("change", function () {
  uploadedFile = this.files[0];
  if (uploadedFile) {
    if (!allowed_types.includes(uploadedFile.type)) {
      return;
    }
    const reader = new FileReader();
    reader.onload = function (e) {
      $(".preview-image").attr("src", e.target.result).show();
    };
    reader.readAsDataURL(uploadedFile);
  }
});

$(document).on("click", "table#cat-table span.delete", function () {
  const this2 = this;
  if (confirm("Bạn thực sự muốn xóa danh mục này ?")) {
    $.ajax({
      type: "POST",
      url: $(this).data("route"),
      data: {
        csrf_token: $("#add-cat-form input[name=csrf_token]").val(),
      },
      success: function (res) {
        $(this2).parent().parent().parent().remove();
        $(`#add-cat-form select option#parent-id-${res.deleted_id}`).remove();
      },
      error: function (xhr, status, error) {
        console.log(xhr.responseJSON.message);
      },
    });
  }
});

$("#add-coupon-form").submit(function (e) {
  e.preventDefault();
  const this2 = this;
  $(this).find("button").prop("disabled", true);
  $(this).find("button").css({
    cursor: "not-allowed",
  });
  $(this).find("button").after(loading_icon);
  const formData = new FormData(this);
  $.ajax({
    type: "POST",
    url: add_coupon_url,
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      $("#coupon-table tbody").prepend(response);
      $(this2).find("button").prop("disabled", false);
      $(this2).find("button").css({
        cursor: "default",
      });
      $(this2).find("button").next("svg").remove();
      $(this2).trigger("reset");
    },
    error: function (xhr, status, error) {
      // console.log(xhr.responseJSON.error_bag);
      notif({
        msg: xhr.responseJSON.message,
        type: "warning",
        position: "center",
        height: "auto",
        top: 80,
        timeout: 5000,
        animation: "slide",
      });
      $(this2).find("button").prop("disabled", false);
      $(this2).find("button").css({
        cursor: "pointer",
      });
      $(this2).find("button").next("svg").remove();
    },
  });
});

if ($.fn.datetimepicker) {
  $(".coupon-time").datetimepicker({
    format: "d-m-Y H:i",
  });
}

$(document).on("click", "table#coupon-table span.delete", function () {
  const this2 = this;
  if (confirm("Bạn thực sực muốn xóa mã giảm giá này ?")) {
    $.ajax({
      type: "POST",
      data: {
        csrf_token: $("#add-coupon-form input[name=csrf_token]").val(),
      },
      url: $(this).data("route"),
      success: function (res) {
        $(this2).parent().parent().parent().remove();
      },
      error: function (xhr, status, error) {
        console.log(xhr);
      },
    });
  }
});

$("#update-coupon-form").submit(function (event) {
  event.preventDefault();
  const this2 = this;
  $(this).find("button").prop("disabled", true);
  $(this).find("button").css({
    cursor: "not-allowed",
  });
  $(this).find("button").after(loading_icon);
  const formData = new FormData(this);
  $.ajax({
    type: "POST",
    url: update_coupon_url,
    data: formData,
    processData: false,
    contentType: false,
    success: function (res) {
      window.location.href = res.coupon_url;
    },
    error: function (xhr, status, error) {
      console.log(JSON.parse(xhr.responseJSON.error_bag));
      $(this2).find("button").prop("disabled", false);
      $(this2).find("button").css({
        cursor: "default",
      });
      $(this2).find("button").next("svg").remove();
    },
  });
});

let order_status_updating = false;
function orderUpdate(button) {
  if (!order_status_updating) {
    $(button).prop("disabled", true);
    $(button).css({
      cursor: "not-allowed",
    });
    const formData = new FormData();
    const button1 = button;
    let status = $("select[name=status]").val();
    formData.append("status", status);
    formData.append("csrf_token", csrf_token);
    $(button).after(loading_icon);
    $.ajax({
      type: "POST",
      url: update_order_status_url,
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        alert(response);
        $(button1).prop("disabled", false);
        $(button1).css({
          cursor: "pointer",
        });
        $(button1).next("svg").remove();
      },
      error: function (xhr, status, error) {
        console.log(xhr);
      },
    });
  }
}

$("#newpass-input").click(function () {
  if ($("#remove-newpass").length) {
    return;
  }
  $(this).after(
    `<button id="remove-newpass" class="btn btn-secondary ml-2">Bỏ</button>
    <div class="position-relative mt-2">
        <input type="password" name="password" class="form-control">
        <svg style="right:10px" width="20" height="20" fill="currentColor" class="bi bi-eye-fill eye-icon position-absolute" viewBox="0 0 16 16">
            <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
            <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
        </svg>
    </div>`
  );
});

$(document).on("click", "#remove-newpass", function () {
  $(this).next().remove();
  $(this).remove();
});

$(document).on("click", "svg.eye-icon", function () {
  if ($("input[name=password]").attr("type") === "text") {
    $("input[name=password]").attr("type", "password");
    $(this)
      .replaceWith(`<svg style="right:10px" width="20" height="20" fill="currentColor" class="bi bi-eye-fill eye-icon position-absolute" viewBox="0 0 16 16">
                            <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                            <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                        </svg>`);
  } else {
    $("input[name=password]").attr("type", "text");
    $(this)
      .replaceWith(`<svg style="right:10px" width="20" height="20" fill="currentColor" class="bi bi-eye eye-icon position-absolute" viewBox="0 0 16 16">
  <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
  <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
</svg>`);
  }
});

const loading_icon = `<svg
    width="44"
    height="44"
    viewBox="0 0 24 24"
    >
    <g>
        <rect width="1.5" height="5" x="11" y="1" fill="currentColor" opacity=".14" />
        <rect
        width="1.5"
        height="5"
        x="11"
        y="1"
        fill="currentColor"
        opacity=".29"
        transform="rotate(30 12 12)"
        />
        <rect
        width="1.5"
        height="5"
        x="11"
        y="1"
        fill="currentColor"
        opacity=".43"
        transform="rotate(60 12 12)"
        />
        <rect
        width="1.5"
        height="5"
        x="11"
        y="1"
        fill="currentColor"
        opacity=".57"
        transform="rotate(90 12 12)"
        />
        <rect
        width="1.5"
        height="5"
        x="11"
        y="1"
        fill="currentColor"
        opacity=".71"
        transform="rotate(120 12 12)"
        />
        <rect
        width="1.5"
        height="5"
        x="11"
        y="1"
        fill="currentColor"
        opacity=".86"
        transform="rotate(150 12 12)"
        />
        <rect
        width="1.5"
        height="5"
        x="11"
        y="1"
        fill="currentColor"
        transform="rotate(180 12 12)"
        />
        <animateTransform
        attributeName="transform"
        calcMode="discrete"
        dur="0.75s"
        repeatCount="indefinite"
        type="rotate"
        values="0 12 12;30 12 12;60 12 12;90 12 12;120 12 12;150 12 12;180 12 12;210 12 12;240 12 12;270 12 12;300 12 12;330 12 12;360 12 12"
        />
    </g>
</svg>`;

if (typeof admin !== "undefined") {
  const date_labels = [
    "0:00",
    "01:00",
    "02:00",
    "03:00",
    "04:00",
    "05:00",
    "06:00",
    "07:00",
    "08:00",
    "09:00",
    "10:00",
    "11:00",
    "12:00",
    "13:00",
    "14:00",
    "15:00",
    "16:00",
    "17:00",
    "18:00",
    "19:00",
    "20:00",
    "21:00",
    "22:00",
    "23:00",
  ];

  $("#date-picker").datepicker({
    dateFormat: "dd/mm/yy",
    onSelect: function (dateText, inst) {
      let dateAsObject = $(this).datepicker("getDate");
      let date = formatDate(dateAsObject);
      $.ajax({
        type: "GET",
        url: `${statistical_url}/date`,
        data: {
          date: date,
        },
        success: function (res) {
          data = res.data;
          console.log(data);
          const dataOrders = [];
          const dataSubtotal = [];
          for (let i = 0; i < 24; i++) {
            let ob = data.find((item) => item.hour === i);
            if (ob) {
              dataOrders.push(ob.total_orders);
              dataSubtotal.push(Number(ob.total));
            } else {
              dataOrders.push(0);
              dataSubtotal.push(0);
            }
          }
          updateChart(null, dataOrders, dataSubtotal);
        },
        error: function (xhr, status, error) {
          console.log(xhr);
        },
      });
    },
  });
  $("#date-picker").datepicker("setDate", new Date());
  const dataOrders = [];
  const dataSubtotal = [];
  for (let i = 0; i < 24; i++) {
    let ob = x.find((item) => item.hour === i);
    if (ob) {
      dataOrders.push(ob.total_orders);
      dataSubtotal.push(Number(ob.total));
    } else {
      dataOrders.push(0);
      dataSubtotal.push(0);
    }
  }
  const ctx = document.getElementById("myChart");
  const myChart = new Chart(ctx, {
    type: "line",
    data: {
      labels: date_labels,
      datasets: [
        {
          label: "Đơn hàng",
          data: dataOrders,
          yAxisID: "orders",
          tension: 0.4,
          backgroundColor: "#4e73df",
          borderWidth: 3,
          borderColor: "#4e73df",
        },
        {
          label: "Doanh thu",
          data: dataSubtotal,
          yAxisID: "subtotal",
          tension: 0.4,
          backgroundColor: "#7ec699",
          borderWidth: 3,
          borderColor: "#7ec699",
        },
      ],
    },
    options: {
      scales: {
        x: {
          grid: {
            display: false,
          },
        },
        orders: {
          beginAtZero: true,
          grid: {
            display: false,
          },
          ticks: {
            callback: function (value) {
              return Math.floor(value);
            },
            maxTicksLimit: Math.max(...dataOrders) + 1,
          },
        },
        subtotal: {
          position: "right",
          beginAtZero: true,

          grid: {
            display: false,
          },
          ticks: {
            callback: function (value) {
              return Math.floor(value);
            },
            maxTicksLimit: Math.max(...dataSubtotal) + 1,
          },
        },
      },
    },
  });
  let statistical_options = ["date", "week", "month", "year"];
  $("#datepicker").datepicker();
  $("#statistical-options li.list-group-item").on("click", function () {
    $("#statistical-options li.list-group-item").removeClass("active");
    $(this).addClass("active");
    let option = $(this).data("option");
    if (statistical_options.includes(option)) {
      switch (option) {
        case "date":
          $("#picker").html(
            `<input type="text" class="form-control" id="date-picker" autocomplete="off">`
          );
          $("#date-picker").datepicker({
            dateFormat: "dd/mm/yy",
            onSelect: function (dateText, inst) {
              var dateAsObject = $(this).datepicker("getDate");
              let date = formatDate(dateAsObject);
              $.ajax({
                type: "GET",
                url: `${statistical_url}/date`,
                data: {
                  date: date,
                },
                success: function (res) {
                  data = res.data;
                  console.log(data);
                  const dataOrders = [];
                  const dataSubtotal = [];
                  for (let i = 0; i < 24; i++) {
                    let ob = data.find((item) => item.hour === i);
                    if (ob) {
                      dataOrders.push(ob.total_orders);
                      dataSubtotal.push(Number(ob.total));
                    } else {
                      dataOrders.push(0);
                      dataSubtotal.push(0);
                    }
                  }
                  updateChart(date_labels, dataOrders, dataSubtotal);
                },
                error: function (xhr, status, error) {
                  console.log(xhr);
                },
              });
            },
          });
          break;
        case "week":
          let startDate;
          let endDate;
          $("#picker").html(
            `<input name="startDate" type="text" class="form-control" id="week-picker" autocomplete="off" />`
          );
          $("#week-picker").datepicker({
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: "dd/mm/yy",
            onSelect: function (dateText, inst) {
              let date = $(this).datepicker("getDate");
              startDate = new Date(
                date.getFullYear(),
                date.getMonth(),
                date.getDate() - date.getDay()
              );
              endDate = new Date(
                date.getFullYear(),
                date.getMonth(),
                date.getDate() - date.getDay() + 6
              );
              let dateFormat =
                inst.settings.dateFormat || $.datepicker._defaults.dateFormat;
              $("#startDate").text(
                $.datepicker.formatDate(dateFormat, startDate, inst.settings)
              );

              $("#endDate").text(
                $.datepicker.formatDate(dateFormat, endDate, inst.settings)
              );
              $(this).val(
                $.datepicker.formatDate(dateFormat, startDate, inst.settings) +
                  " - " +
                  $.datepicker.formatDate(dateFormat, endDate, inst.settings)
              );
              let start_date = $.datepicker.formatDate(
                "yy-mm-dd",
                startDate,
                inst.settings
              );
              let end_date = $.datepicker.formatDate(
                "yy-mm-dd",
                endDate,
                inst.settings
              );
              const start = new Date(start_date);
              const end = new Date(end_date);
              const dates = [];
              for (
                let current_date = start;
                current_date <= end;
                current_date.setDate(current_date.getDate() + 1)
              ) {
                const day = current_date.getDate().toString().padStart(2, "0");
                const month = (current_date.getMonth() + 1)
                  .toString()
                  .padStart(2, "0");
                const formattedDate = `${day}/${month}`;
                dates.push(formattedDate);
              }
              $.ajax({
                type: "GET",
                url: `${statistical_url}/week`,
                data: {
                  start_date: start_date,
                  end_date: end_date,
                },
                success: function (res) {
                  let data = res.data;
                  const dataOrders = [];
                  const dataSubtotal = [];
                  for (let i = 0; i < dates.length; i++) {
                    let ob = data.find((item) => {
                      let date_parts = item.date.split("-");
                      if (`${date_parts[2]}/${date_parts[1]}` === dates[i]) {
                        return true;
                      }
                      return false;
                    });
                    if (ob) {
                      dataOrders.push(ob.total_orders);
                      dataSubtotal.push(Number(ob.total));
                    } else {
                      dataOrders.push(0);
                      dataSubtotal.push(0);
                    }
                  }
                  updateChart(dates, dataOrders, dataSubtotal);
                },
                error: function (xhr, status, error) {
                  console.log(xhr);
                },
              });
            },
          });
          break;
        case "month":
          $("#picker").html(
            `<input id="month-picker" class="form-control" type="text" autocomplete="off" />`
          );
          $("#month-picker").MonthPicker({
            Button: false,
            OnAfterChooseMonth: function (selectedDate) {
              let month = selectedDate.getMonth() + 1;
              let year = selectedDate.getFullYear();
              let start_date = new Date(year, month - 1, 1);
              let end_date = new Date(year, month, 0);
              const dates = [];
              for (
                let current_date = start_date;
                current_date <= end_date;
                current_date.setDate(current_date.getDate() + 1)
              ) {
                const day = current_date.getDate().toString().padStart(2, "0");
                const month = (current_date.getMonth() + 1)
                  .toString()
                  .padStart(2, "0");
                dates.push(`${day}/${month}`);
              }
              $.ajax({
                type: "GET",
                url: `${statistical_url}/month`,
                data: {
                  month: month,
                  year: year,
                },
                success: function (res) {
                  let data = res.data;
                  const dataOrders = [];
                  const dataSubtotal = [];
                  for (let i = 0; i < dates.length; i++) {
                    let ob = data.find((item) => {
                      let date_parts = item.date.split("-");
                      if (`${date_parts[2]}/${date_parts[1]}` === dates[i]) {
                        return true;
                      }
                      return false;
                    });
                    if (ob) {
                      dataOrders.push(ob.total_orders);
                      dataSubtotal.push(Number(ob.total));
                    } else {
                      dataOrders.push(0);
                      dataSubtotal.push(0);
                    }
                  }
                  updateChart(dates, dataOrders, dataSubtotal);
                },
                error: function (xhr, status, error) {
                  console.log(xhr);
                },
              });
            },
          });
          break;
        case "year":
          $("#picker").html(
            `<input id="year-picker" class="form-control" type="text" autocomplete="off"/>`
          );
          $("#year-picker").yearpicker({
            onChange: function (value) {
              if (value !== null) {
                $.ajax({
                  type: "GET",
                  url: `${statistical_url}/year`,
                  data: {
                    year: value,
                  },
                  success: function (res) {
                    let data = res.data;
                    console.log(data);
                    const dataOrders = [];
                    const dataSubtotal = [];
                    const labels = [];
                    for (let i = 1; i <= 12; i++) {
                      let ob = data.find((item) => item.month === i);
                      if (ob) {
                        dataOrders.push(ob.total_orders);
                        dataSubtotal.push(Number(ob.total));
                      } else {
                        dataOrders.push(0);
                        dataSubtotal.push(0);
                      }
                      labels.push(`${i > 9 ? `${i}` : `0${i}`}/${value}`);
                    }
                    updateChart(labels, dataOrders, dataSubtotal);
                  },
                  error: function (xhr, status, error) {
                    console.log(xhr);
                  },
                });
              }
            },
          });
          break;
        default:
      }
    }
  });
  function updateChart(newLabels = null, newDataOrders, newDataSubtotal) {
    if (newLabels) {
      myChart.data.labels = newLabels;
    }
    myChart.data.datasets[0].data = newDataOrders;
    myChart.data.datasets[1].data = newDataSubtotal;
    myChart.options.scales.orders.ticks = {
      callback: function (value, index, values) {
        return Math.floor(value);
      },
      maxTicksLimit: Math.max(...newDataOrders) + 1,
    };
    myChart.options.scales.subtotal.ticks = {
      callback: function (value, index, values) {
        return Math.floor(value);
      },
      maxTicksLimit: Math.max(...newDataSubtotal) + 1,
    };
    myChart.update();
  }
  function formatDate(date) {
    const year = date.getFullYear();
    const month = (date.getMonth() + 1).toString().padStart(2, "0"); // Months are zero-indexed
    const day = date.getDate().toString().padStart(2, "0");

    return `${year}-${month}-${day}`;
  }
}
