function goTo(path) {
	window.location.href = path;
}

document.getElementById('search-input').addEventListener('keypress', (e) => {
	if(e.charCode === 13) {
		window.location.href = "/search?search=" + document.getElementById('search-input').value;
	}
});