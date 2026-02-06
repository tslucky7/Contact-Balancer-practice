import './style.css'

const form = document.getElementById("inquiryForm") as HTMLFormElement;
const result = document.getElementById("result") as HTMLPreElement;

form.addEventListener("submit", async (e) => {
  e.preventDefault();

  const fd = new FormData(form);
  const payload = {
    name: String(fd.get("name") ?? ""),
    email: String(fd.get("email") ?? ""),
    subject: String(fd.get("subject") ?? ""),
    message: String(fd.get("message") ?? ""),
  };

  const res = await fetch("/api/inquiries.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(payload),
  });

  const json = await res.json().catch(() => ({}));
  result.textContent = JSON.stringify(json, null, 2);

  if (res.ok) form.reset();
});
