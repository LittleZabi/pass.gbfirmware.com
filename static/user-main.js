const container_left = document.querySelector(".container .left-list");
const search = document.querySelector("#search_query");
const result = document.querySelector(".container .right .result");

const Copy = (str) => {
  let textarea = document.createElement("textarea");
  textarea.style.position = "fixed";
  textarea.style.top = 0;
  textarea.style.left = 0;
  textarea.style.width = "2em";
  textarea.style.height = "2em";
  textarea.style.border = "none";
  textarea.style.outline = "none";
  textarea.style.boxShadow = "none";
  textarea.style.background = "transparent";
  textarea.value = str;
  document.body.appendChild(textarea);
  textarea.focus();
  textarea.select();
  try {
    let success = document.execCommand("copy");
    let msg = success ? "successfully" : "unsuccessfully";
  } catch (error) {
    console.log("Oops, unalbe top copy");
  }
  document.body.removeChild(textarea);
};

const Delete = (id) => {
  console.log(id);
  axios.get("./admin-api.php?delete=" + id).then((res) => {
    const file_info = document.querySelector(".right .file-info");
    file_info.innerHTML = '<span class="no-file">No file selected</span>';
    setMessage("Delete successfully", "alert");
    getUsers();
  });
};
const GrabUser = (id) => {
  const file_info = document.querySelector(".right #user-form fieldset");
  axios.get("./admin-api.php?get_user=" + id).then((res) => {
    console.log(res.data);
    let info = res.data;
    let html = `
              <legend>Edit and save user information</legend>
              <h3 style="margin:12px 0;margin-top:-20px;color:#ffffff96;font-style:italic;">User ID ${info.id}</h3>
              <label for="username">Update username</label>
              <input required type="text" value="${info.username}" name="username" id="username" placeholder="Enter user fullname...">
              <label for="email">Update email address</label>
              <input required type="text" name="email" id="email" value="${info.email}" placeholder="Enter email address...">
              <label for="password">Enter new password<small> (old: ${info.password})</small></label>
              <input required value="${info.password}" type="password" name="password" id="password" placeholder="Enter password...">
              <label for="re-password">Enter new password again<small> (old: ${info.password})</small></label>
              <input required value="${info.password}" type="password" name="re-password" id="re-password" placeholder="Enter password again...">
              <input type="hidden" name="user-manage-action" value="update-user">
              <input type="hidden" name="user-id" value="${info.id}">
              <div style="display: flex;justify-content: space-around;">
              <button type="submit">Save user</button>
              <button type="button" onclick="handleUserDelete(${info.id})" style="background: orangered">Delete User</button></div>
    `;
    file_info.innerHTML = html;
  });
};
async function handleUserDelete(id) {
  let c = window.confirm("Do you want to delete this user!");
  if (c) {
    await axios
      .get("./admin-api.php?delete_user=" + id)
      .then((e) => {
        if (e.data == "success") {
          alert("user successfully deleted!");
          window.location.href = window.location.href;
        }
      })
      .catch((e) => {
        console.log(e.message);
      });
  }
}

const setMessage = (msg, type = "info") => {
  const message = document.querySelector("#message");
  const msg_span = document.querySelector("#message span");
  msg_span.className = "";
  if (msg.length > 0) {
    msg_span.classList.add(type);
    msg_span.innerHTML = msg;
  } else {
    message.style.display = "none";
  }
};

const getUsers = () => {
  axios
    .get("./admin-api.php/?get_users=1")
    .then((response) => {
      const res = response.data;
      container_left.innerHTML = "";
      console.log("data: ", res);

      if (res.length > 0)
        if (res === "userNotLogged") {
          window.location.href = "./login.php";
        } else if (res === "notFound") {
          console.log("data not found...");
        } else {
          res.map((e) => {
            container_left.innerHTML += `
          <section onClick="GrabUser(${e.id})">
              <span class="title">${e.id} -> ${e.username}</span>
              <span class="pass">${e.email}</span>
          </section>
        `;
          });
        }
    })
    .catch((error) => console.error(error));
};
getUsers();
