// Simulated post data (replace with actual data from your backend)
const posts = [
    { id: 1, username: "Radgemi Arma", content: "Hello, Teknospace!", timestamp: "2024-06-29 10:00:00" },
    { id: 2, username: "Radgemi Arma", content: "Another post here.", timestamp: "2024-06-29 11:30:00" }
];

function displayPosts() {
    const postList = document.getElementById('post-list');
    postList.innerHTML = '';

    posts.forEach(post => {
        const postElement = document.createElement('div');
        postElement.classList.add('post');
        postElement.innerHTML = `
            <h3>${post.username}</h3>
            <p>${post.content}</p>
            <small>${post.timestamp}</small>
        `;
        postList.appendChild(postElement);
    });
}

// Call this function when the page loads
displayPosts();

// Add event listeners for changing photos
document.getElementById('change-profile').addEventListener('click', () => {
    console.log('Change profile photo clicked');
    // Implement photo change functionality here
});

document.getElementById('change-cover').addEventListener('click', () => {
    console.log('Change cover photo clicked');
    // Implement cover photo change functionality here
});

