const ROOT_OP = "./modules/op.php";
const removeActive = () => {
  try {
    const k = document.querySelectorAll(".list-group .active");

    if (k.length > 0) {
      k.forEach((e) => {
        e.classList.remove("active");
      });
    }
  } catch (error) {
    console.log(error.message);
  }
};
const handleShowChilds = async (id, parent, element) => {
  removeActive();
  element.classList.add("active");
  const loading = document.querySelector("#show-loading");
  loading.innerHTML = "Loading...";
  loading.style.display = "block";
  await axios
    .get(`${ROOT_OP}?show-childs=1&id=${id}`)
    .then((e) => {
      loading.style.display = "none";
      if (e.data === "") {
        loading.innerHTML = "No Data found!";
        loading.style.display = "block";
        dataList([], parent);
      } else {
        dataList(e.data, parent);
      }
    })
    .catch((e) => {
      loading.style.display = "none";
      console.error(e.message);
    });
};
const handleSearch = async (input) => {
  const loading = document.querySelector("#item-list");
  if (input.value.length > 0) {
    loading.innerHTML = "Searching...";
    loading.style.display = "block";
    await axios
      .get(`${ROOT_OP}?search=1&q=${input.value}`)
      .then((e) => {
        if (e.data === "") {
          loading.innerHTML = "No Data found!";
          loading.style.display = "block";
          SearchResult(false);
        } else {
          console.log(e.data);
          SearchResult(e.data);
        }
      })
      .catch((e) => {
        loading.style.display = "none";
        console.error(e.message);
      });
  } else {
    SearchResult(filesList);
  }
};

SearchResult = (data) => {
  const view = document.querySelector("#item-list");
  if (data && data.length > 0) {
    html = "";
    data.map((e) => {
      html += `
        <span onclick="handleShowChilds(${e.id},'${
        e.filename === "" ? e.base_url : e.filename
      }', this)" class="${
        e.complete == 1 ? "done" : ""
      } list-group-item list-group-item-action flex-column align-items-start">
                      <div class="d-flex w-100 justify-content-between">
                          <h5 class="mb-1">${
                            e.filename === "" ? e.base_url : e.filename
                          }</h5>
                          <small>${new Date(e.createdAt).toDateString()}</small>
                      </div>
                      <p class="mb-1">${e.base_url}</p>
                      <p class="mb-1"><b>Final: </b>${
                        e.finalLink == "" ? "There is not output" : e.finalLink
                      }</p>
                      <p class="mb-1"><b>Bot Operation: ${
                        e.referToBot == 1
                          ? " Bot is processing on it"
                          : "Not Refer to Bot!"
                      }</b> </p>
                      <span><button class="btn btn-primary" onclick="handleAddNewChild(${
                        e.id
                      }, this)">Add Child</button></span>
                  </span>
        `;
    });
    view.innerHTML = html;
  } else {
    view.innerHTML = "<br><br/><h4>Not found</h4><br><br/>";
  }
};
const dataList = (data, parent) => {
  const view = document.querySelector("#item-view");
  if (data.length < 1) {
    view.innerHTML = "";
    return 1;
  }
  // data = JSON.parse(data);
  let html = `<div class="wrap">
    <div class="super-container">
          <div class="form" style="min-width: 400px;">
              <h3>${parent} childs</h3>`;
  let list = "";
  data.map((e) => {
    list += `
              <div class="form-group mx-sm-3 mb-2 item">
                  <label for="filename" id="file" style="text-align: left;display:block;font-size: 14px"><h4>${e.new_filename}</h4></label>
                  <a href="./view.php?slug=${e.token}"><b>Open File Request Page.</b></a>
                  <br/>
                  <br/>
                  
                  <input type="text" id="newName${e.id}" value='${e.new_filename}' class="form-control" placeholder="Enter child name here...">
                  <br/>
                  <button onclick="changeName(${e.id}, this)" type="submit" name="submit-link" class="btn btn-primary" style="width: 200px;margin:auto;">update name</button>
                  <button onclick="handleDelete(${e.id}, this)" type="submit" name="submit-link" class="btn btn-danger" style="width: 200px;margin:auto;">Delete</button>
                  </div>
                
              <br>
          `;
  });
  html += list;
  html += `</div>
      </div>
      </div>
      `;
  view.innerHTML = html;
};
const changeName = async (id, element) => {
  const newName = document.querySelector("#newName" + id);
  element.innerHTML = "Loading...";
  element.disabled = true;
  await axios
    .get(`${ROOT_OP}?update-name=1&id=${id}&to=${newName.value}`)
    .then((e) => {
      element.innerHTML = "Add";
      element.disabled = false;
      alert("name change successfuly to " + newName.value);
      try {
        element.parentNode.childNodes[1].innerHTML = `current filename: <b>${newName.value}</b>`;
      } catch (error) {
        console.error(error.message);
      }
    })
    .catch((e) => {
      element.innerHTML = "Add";
      element.disabled = false;
      console.log(e.message);
    });
};
const handleDelete = async (id, element) => {
  element.innerHTML = "Loading...";
  element.disabled = true;
  await axios
    .get(`${ROOT_OP}?delete-child=1&id=${id}`)
    .then((e) => {
      element.innerHTML = "Delete";
      element.disabled = false;
      alert("Item deleted successfully!");
      try {
        element.parentNode.style.display = "none";
      } catch (error) {
        console.log(error.message);
      }
    })
    .catch((e) => {
      element.innerHTML = "Delete";
      element.disabled = false;
      console.log(e.message);
    });
};
const handleAddNewChild = async (id, element) => {
  const modal = document.querySelector("#modal");
  const html = `
      <div class="wrap">
      <span class="close" onclick="closeModal()">&times;</span>
          <div class="form" style="min-width: 400px;">
              <h3>Enter Child Details</h3>
              <div class="form-group mx-sm-3 mb-2">
                  <label for="filename" style="text-align: left;display:block;font-size: 14px">Enter child name</label>
                  <input type="text" id="filename" class="form-control" placeholder="Enter child name here...">
              </div>
              <br>
              <button onclick="handleAdd(${id}, this)" type="submit" name="submit-link" class="btn btn-primary" style="width: 200px;margin:auto;">Add</button>
          </div>
      </div>
      `;
  modal.innerHTML = html;
  modal.style.display = "flex";
};
const handleAdd = async (id, element) => {
  const filename = document.querySelector("#filename");
  element.innerHTML = "Loading...";
  element.disabled = true;
  await axios
    .get(`${ROOT_OP}?add-child=1&id=${id}&filename=${filename.value}`)
    .then((e) => {
      element.innerHTML = "Add";
      element.disabled = false;

      if (e.data === "exist") {
        alert("file name already exist choose another one");
      }
      if (e.data === "success") {
        alert("successfully added...");
      }
      closeModal();
    })
    .catch((e) => {
      element.innerHTML = "Add";
      element.disabled = false;
      console.log(e.message);
    });
};
const addNewFileInList = async () => {
  try {
    document.querySelector("#exampleModalLabel").innerHTML = "Add new file";
    let form = document.querySelector(".modal-body form");
    let html = `
    <div class="form-group">
        <input onkeyup="SearchFile(this)" type="text" placeholder="Search file, id, title, etc..." class="form-control" id="search">
        <div class="search-list-item"></div>
        <label for="recipient-name" class="col-form-label">Enter Title.</label>
        <input type="text" class="form-control" id="filename">
        <label for="recipient-name" class="col-form-label">Enter file password.</label>
        <input type="text" class="form-control" id="password">
        <label for="recipient-name" class="col-form-label">File ID.</label>
        <input type="text" disabled value="" class="form-control" id="fileid">
    </div>
    `;
    form.innerHTML = html;
    const buttons = document.querySelector(".modal-content .modal-footer");
    html = `
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="LockFile(this)">Add File</button>
    `;
    buttons.innerHTML = html;
  } catch (error) {
    console.log(error.message);
  }
};
const LockFile = () => {
  const setFileId = document.querySelector("#fileid");
  const setFileTitle = document.querySelector("#filename");
  const setFilePassText = document.querySelector("#password");
  if (setFilePassText.value < 1) {
    setMessage("set a password to file then you can save it.", "alert");
    return 1;
  }
  let data = {
    setFile: 1,
    file_id: setFileId.value,
    file_title: setFileTitle.value,
    file_pass: setFilePassText.value,
  };
  $.ajax({
    method: "POST",
    // url: `${ROOT_OP}?setFile=1&file_id=${data.file_id}&file_title=${data.file_title}&file_pass=${data.file_pass}`
    url: ROOT_OP,
    data,
    success: (res) => {
      console.log(res);
      if (res === "success") {
        setFilePassText.value = "";
        search.value = "";
        setMessage((msg = "Successfully created..."), (type = "success"));
      } else if (res === "updated") {
        setMessage((msg = "Successfully updated!"), (type = "success"));
      }
      // reload();
    },
  });
};

const setMessage = (msg, type = "info") => {
  alert(msg);
  reload();
  // const message = document.querySelector("#message");
  // const msg_span = document.querySelector("#message span");
  // msg_span.className = "";
  // if (msg.length > 0) {
  //   msg_span.classList.add(type);
  //   msg_span.innerHTML = msg;
  // } else {
  //   message.style.display = "none";
  // }
};
const reload = () => (window.location.href = window.location.href);
const SearchFile = (search) => {
  let result = document.querySelector(".search-list-item");
  if (search.value.length > 0) {
    $.ajax({
      method: "POST",
      url: ROOT_OP + "/?q=" + search.value,
      success: (res) => {
        if (res.length > 0) {
          res = JSON.parse(res);
          result.innerHTML = "";
          console.log(res);
          res.map((e, index) => {
            result.style.display = "block";
            result.innerHTML += `
                      <div onClick="readFile({file_id:${e.file_id},file_title:'${e.title}'})"><small>${e.file_id} -></small> ${e.title}</div>
                  `;
          });
        }
      },
    });
  } else {
    result.innerHTML = "";
    result.style.display = "none";
  }
};
const closeSearch = () => {
  let result = document.querySelector(".search-list-item");
  result.innerHTML = "";
  result.style.display = "none";
};
const readFile = (item) => {
  document.getElementById("filename").value = item.file_title;
  document.getElementById("fileid").value = item.file_id;
  closeSearch();
};
const closeModal = () => {
  const modal = document.querySelector("#modal");
  modal.style.display = "none";
};

const handleAddNew = (element) => {
  let c = document.querySelector("#filecache").value;
  let f = document.querySelector("#filename").value;
  let u = document.querySelector("#fileurl").value;
  if (f === "") {
    alert("Enter filename.");
    return;
  }
  if (u === "") {
    alert("Enter file base url.");
    return;
  }

  $.ajax({
    url: ROOT_OP,
    method: "GET",
    data: {
      "submit-link": 1,
      "file-link": u,
      "file-name": f,
      "cache-type": c === "" ? "Featured" : c,
    },
    success: (e) => {
      let res = JSON.parse(e);
      console.log(res);
      if (res.message == "exist") {
        window.alert(
          `File is already exist with ID ${res.id} check in files list.`
        );
      }
      if (res.message == "success") {
        window.location.href = window.location.href;
      }
    },
  });
};
const handleSaveEdits = (element) => {
  let i = document.querySelector("#fileID").value;
  let c = document.querySelector("#filename").value;
  let f = document.querySelector("#password").value;
  element.innerHTML = "loading...";
  element.disabled = true;
  if (c === "") {
    alert("Enter filename.");
    return;
  }
  if (f === "") {
    alert("Enter password.");
    return;
  }
  $.ajax({
    url: ROOT_OP,
    method: "POST",
    data: {
      setFile: 1,
      file_pass: f,
      file_title: c,
      file_id: i,
    },
    success: (e) => {
      element.innerHTML = "Save changes";
      element.disabled = true;
      console.log(e);
      if (e == "updated") {
        setMessage((msg = "successfully updated!"), (type = "success"));
      }
    },
    onerror: (e) => {
      element.innerHTML = "Save changes";
      element.disabled = true;
      setMessage((msg = "Error: " + e.message), (type = "error"));
    },
  });
};
const handleEditFile = (item) => {
  try {
    document.querySelector("#exampleModalLabel").innerHTML = "Edit file";
    let form = document.querySelector(".modal-body form");
    let html = `
        <div class="form-group">
            <label  class="col-form-label">Update File Title.</label>
            <input value="${item.file_id}" type="hidden" id="fileID">
            <input value="${item.filename}" type="text" class="form-control" id="filename">
            <label  class="col-form-label">Update Passwords.</label>
            <input value="${item.password}" type="text" class="form-control" id="password">
        </div>
        `;
    form.innerHTML = html;
    const buttons = document.querySelector(".modal-content .modal-footer");
    html = `
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="handleSaveEdits(this)">Save changes</button>
        `;
    buttons.innerHTML = html;
  } catch (error) {}
};
const addNewUser = () => {
  try {
    document.querySelector("#exampleModalLabel").innerHTML = "New user";
    let form = document.querySelector(".modal-body form");
    let html = `
        <div class="form-group">
            <label  class="col-form-label">new user fullname.</label>
            <input  type="text" class="form-control" id="username">
            <label  class="col-form-label">new user email address.</label>
            <input  type="text" class="form-control" id="email">
            <label  class="col-form-label">user password.</label>
            <input  type="text" class="form-control" id="password">
            <input  type="hidden" value="new-user" id="user-action">
 <input  type="hidden" value="" id="user-id">
        
        </div>
        `;
    form.innerHTML = html;
    const buttons = document.querySelector(".modal-content .modal-footer");
    html = `
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="handleNewUser(this)">Add user</button>
        `;
    buttons.innerHTML = html;
  } catch (error) {}
};
const handleNewUser = (element) => {
  element.innerHTML = "Loading...";
  let username = document.querySelector("#username").value;
  let email = document.querySelector("#email").value;
  let password = document.querySelector("#password").value;
  let action = document.querySelector("#user-action").value;
  let userID = document.querySelector("#user-id").value;
  if (username === "" || email === "") {
    alert("Fill all boxes something inputs is empty!");
    return;
  }
  if (password === "") {
    alert("Fill the password input its required");
    return;
  }
  $.ajax({
    url: ROOT_OP,
    method: "POST",
    data: {
      addNewUser: 1,
      "user-manage-action": action,
      username,
      password,
      email,
      "user-id": userID,
    },
    success: (e) => {
      console.log(e);
      if (e === "success" || e === "updated")
        setMessage(
          (msg = "operation successfully performed!"),
          (type = "success")
        );
      else if (e === "inputNotFilled")
        setMessage(
          (msg = "Check your inputs and filled its not correct!"),
          (type = "error")
        );
      else if (e === "inputNotFilled")
        setMessage(
          (msg = "Check your inputs and filled its not correct!"),
          (type = "error")
        );
      else if (e === "userNotExist") {
        setMessage(((msg = "This user is not exist!"), (type = "alert")));
      } else if (e === "userExist") {
        setMessage(
          ((msg =
            "This user is already exist please choose another email address!"),
          (type = "alert"))
        );
      } else if (e === "userNotLogged")
        setMessage(
          (msg = "admin is not logged or session timeout please login again!"),
          (type = "error")
        );
      else {
        setMessage((msg = e), (type = "alert"));
        console.log(e);
      }
    },
  });
};

const handleDeleteFile = (id) => {
  let k = confirm("Do you want to Delete! click ok to delete.");
  if (k) {
    $.ajax({
      url: ROOT_OP,
      method: "GET",
      data: {
        delete: id,
      },
      success: (e) => {
        setMessage((msg = "Deleted successfully"), (type = "success"));
      },
    });
  }
};
const handleEditUser = (user) => {
  console.log(user);
  try {
    document.querySelector("#exampleModalLabel").innerHTML = "Update user";
    let form = document.querySelector(".modal-body form");
    let html = `
        <div class="form-group">
            <label  class="col-form-label">update fullname.</label>
            <input  type="text" class="form-control" value="${user.username}" id="username">
            <label  class="col-form-label">user email address.</label>
            <input  type="text" class="form-control" value="${user.email}" id="email">
            <label  class="col-form-label">update password.</label>
            <input  type="text" class="form-control" value="${user.password}" id="password">
            <input  type="hidden" value="update-user" id="user-action">
            <input  type="hidden" value="${user.id}" id="user-id">

            
        </div>
        `;
    form.innerHTML = html;
    const buttons = document.querySelector(".modal-content .modal-footer");
    html = `
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="handleNewUser(this)">Update user</button>
        `;
    buttons.innerHTML = html;
  } catch (error) {
    console.log(error.message);
  }
};

async function handleUserDelete(id) {
  let c = window.confirm("Do you want to delete this user!");
  if (c) {
    $.ajax({
      url: ROOT_OP,
      data: {
        delete_user: id,
      },
      success: (e) => {
        if (e == "success") {
          setMessage((msg = "successfully deleted!"), (type = "success"));
        } else {
          console.log(e);
        }
      },
      onerror: (e) => {
        console.error(e.message);
      },
    });
  }
}

const saveChildChanges = async (element) => {
  const y = element.innerHTML;
  element.innerHTML = "Loading...";
  let id = document.querySelector("#fileID").value;
  let renamedLink = document.querySelector("#renamed").value;
  let to = document.querySelector("#filename").value;
  let pid = document.querySelector("#pid").value;
  let parentName = document.querySelector("#parent-name").value;
  let finalLink = document.querySelector("#final-link").value;
  if (to === "") {
    alert("Enter filename.");
    return;
  }
  await $.ajax({
    url: ROOT_OP,
    method: "POST",
    async: true,
    data: {
      updateName: 1,
      to,
      id,
      pid,
      parentName,
      finalLink,
      renamedLink,
    },
    success: (e) => {
      element.innerHTML = y;
      console.log("res: ", e);
      if (e === "success") window.location.href = window.location.href;
    },
  });
};
