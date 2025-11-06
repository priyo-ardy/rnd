// const csrfToken = document.querySelector('input[name="app_token"]').value;

async function fetchData(url, method, body = null) {
  let options = {
    method: method,
    headers: {
      // "X-CSRF-TOKEN": csrfToken,
    },
  };

  if (body) {
    if (body instanceof FormData) {
      options.body = body;
      options.contentType = false;
      options.processData = false;
    } else {
      options.headers["Content-Type"] = "application/json";
      options.body = body;
    }
  }

  try {
    const response = await fetch(url, options);
    const contentType = response.headers.get("Content-Type");

    // Cek apakah respons tidak ok
    if (!response.ok) {
      const responseText = await response.text(); // Ambil teks respons
      if (contentType && contentType.includes("application/json")) {
        const errorData = JSON.parse(responseText);
        throw errorData;
      } else if (contentType && contentType.includes("text/html")) {
        const parser = new DOMParser();
        const doc = parser.parseFromString(responseText, "text/html");
        const errorMessage =
          doc.querySelector("p")?.textContent || "Error occurred";
        throw { message: errorMessage };
      } else {
        throw new Error("Unexpected content type");
      }
    }

    // Jika respons ok, ambil data JSON
    if (contentType && contentType.includes("application/json")) {
      return await response.json(); // Mengembalikan data JSON
    } else {
      return await response.text(); // Mengembalikan teks jika bukan JSON
    }
  } catch (error) {
    throw error; // Melempar error untuk ditangani di tempat lain
  }
}
