import "./bootstrap"

// いいね機能のJavaScript
window.toggleLike = (productId) => {
  fetch(`/api/products/${productId}/like`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
    },
  })
    .then((response) => response.json())
    .then((data) => {
      const icon = document.getElementById(`like-icon-${productId}`)
      const count = document.getElementById(`like-count-${productId}`)

      if (data.isLiked) {
        icon.classList.add("text-red-500", "fill-current")
      } else {
        icon.classList.remove("text-red-500", "fill-current")
      }

      count.textContent = data.likeCount
    })
    .catch((error) => {
      console.error("Error:", error)
    })
}

