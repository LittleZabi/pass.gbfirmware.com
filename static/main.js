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

const pass_search = document.querySelector("#search_in_passwords");
pass_search.addEventListener("click", () => {
  if (pass_search.value.length < 1) {
    getPassFiles();
  }
});

pass_search.addEventListener("keyup", (e) => {
  if (pass_search.value.length > 0) {
    axios
      .get("./admin-api.php/?pass_search=" + pass_search.value)
      .then((res) => {
        container_left.innerHTML = "";
        res = res.data;
        if (res.length > 0) {
          res.map((e) => {
            container_left.innerHTML += `
        <section onClick="GrabFile(${e.file_id})">
                    <span class="title">#${e.id} - ${e.file_title}</span>
                    <span class="pass">${e.file_pass}</span>
                </section>
        `;
          });
        } else {
          container_left.innerHTML = "";
        }
      });
  } else {
    result.innerHTML = "";
    result.style.display = "none";
  }
});

const Delete = (id) => {
  console.log(id);
  axios.get("./admin-api.php?delete=" + id).then((res) => {
    const file_info = document.querySelector(".right .file-info");
    file_info.innerHTML = '<span class="no-file">No file selected</span>';
    setMessage("Delete successfully", "alert");

    getPassFiles();
  });
};
const GrabFile = (id) => {
  const file_info = document.querySelector(".right .file-info");
  axios.get("./admin-api.php?get_file=" + id).then((res) => {
    let data = res.data;
    console.log();
    if (data.file_id && data.file_title) {
      let userInfo =
        data.user != undefined && data.user.username != undefined
          ? `Uploaded By: ${data.user ? data.user.username : "Admin"}<br/>
          Email: ${data.user.email}<br/>
          user ID: ${data.user.id}
        `
          : "";
      let html =
        userInfo +
        `
      
      <br>
      <br>
      <span class="id">${
        data.id !== undefined ? `#${data.id} - ` : "## - "
      }</span>
      <span class="title" id="title">${data.file_title}</span><br>
      <span class="file-id">file id: ${data.file_id}</span>
      <textarea name="password" class="password" id="file-password" placeholder="Enter password">${
        data.file_pass !== undefined ? data.file_pass : ""
      }</textarea>
      <input type="hidden" id="file-id" value="${data.file_id}">
      <input type="hidden" id="file-title" value="${data.file_title}">
      ${
        data.file_pass
          ? `<button class="delete-btn" onClick="Delete(${data.file_id})">Delete</button>`
          : ""
      }
        
      <button onClick="LockFile()">set password</button>
    `;
      file_info.innerHTML = html;
    }
  });
};

const readFile = (data) => {
  const file_info = document.querySelector(".right .file-info");
  result.innerHTML = "";
  result.style.display = "none";
  axios.get("./admin-api.php?get_file=" + data.file_id).then((res) => {
    if (res.data !== "notFound") {
      data = res.data;
    }
    if (data.file_id && data.file_title) {
      let html = `
      <span class="id">${
        data.id !== undefined ? `#${data.id} - ` : "## - "
      }</span>
      <span class="title" id="title">${data.file_title}</span><br>
      <span class="file-id">file id: #${data.file_id}</span>
      <textarea name="password" class="password" id="file-password" placeholder="Enter password">${
        data.file_pass !== undefined ? data.file_pass : ""
      }</textarea>
      <input type="hidden" id="file-id" value="${data.file_id}">
      <input type="hidden" id="file-title" value="${data.file_title}">
      ${
        data.file_pass
          ? `<button class="delete-btn" onClick="Delete(${data.file_id})">Delete</button>`
          : ""
      }
      <button onClick="LockFile()">set password</button>
    `;
      file_info.innerHTML = html;
    }
  });
};
const LockFile = () => {
  const setFileId = document.querySelector(".right input#file-id");
  const setFileTitle = document.querySelector(".right input#file-title");
  const setFilePassText = document.querySelector(
    ".right textarea#file-password"
  );
  if (setFilePassText.value < 1) {
    setMessage("set a password to file then you can save it.", "alert");
    return 1;
  }
  let data = {
    setFilePass: 1,
    file_id: setFileId.value,
    file_title: setFileTitle.value,
    file_pass: setFilePassText.value,
  };
  axios
    .get(
      `./admin-api.php?setFile=1&file_id=${data.file_id}&file_title=${data.file_title}&file_pass=${data.file_pass}`
    )
    .then((res) => {
      if (res.data === "success") {
        setFilePassText.value = "";
        search.value = "";
        setMessage("Successfully created...");
      } else if (res.data === "updated") {
        setMessage("Successfully updated...");
      }
      getPassFiles();
    });
};
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

search.addEventListener("click", () => {
  if (search.value.length < 1) {
    result.innerHTML = "";
    result.style.display = "none";
  }
});

search.addEventListener("keyup", (e) => {
  if (search.value.length > 0) {
    axios.get("./admin-api.php/?q=" + search.value).then((res) => {
      res = res.data;
      if (res.length > 0) {
        result.innerHTML = "";
        res.map((e, index) => {
          result.style.display = "block";
          result.innerHTML += `
                  <span class="title" onClick="readFile({file_id:${
                    e.file_id
                  },file_title:'${e.title}'})" style="animation-delay: ${
            index * 100
          }ms">#${e.file_id} - ${e.title}</span>
              `;
        });
      }
    });
  } else {
    result.innerHTML = "";
    result.style.display = "none";
  }
});

const getPassFiles = () => {
  axios
    .get("./admin-api.php/?pass_files=1")
    .then((response) => {
      const res = response.data;
      container_left.innerHTML = "";
      if (res.length > 0)
        if (res === "userNotLogged") {
          window.location.href = "./login.php";
        } else if (res === "notFound") {
          console.log("data not found...");
        } else {
          res.map((e) => {
            container_left.innerHTML += `
              <section onClick="GrabFile(${e.file_id})">
                    <span class="title">#${e.id} - ${e.file_title}</span>
                    <span class="pass">${e.file_pass}</span>
              </section>
        `;
          });
        }
    })
    .catch((error) => console.error(error));
};
getPassFiles();
