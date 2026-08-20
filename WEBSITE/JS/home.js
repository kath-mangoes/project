
//--------------- SlideShow ----------------
let slideIndex = 0;
    function showSlides() {
        let slides = document.querySelectorAll(".slide");
        slides.forEach((slide) => { slide.style.display = "none"; });
        slideIndex++;
        if (slideIndex > slides.length) { slideIndex = 1; }
        slides[slideIndex - 1].style.display = "block";
        setTimeout(showSlides, 3000);
    }
    document.addEventListener("DOMContentLoaded", showSlides);

//----------------- Weather API -----------------
async function fetchWeather() {
    const apiKey = "dbb6231c0c15dd830aa1217883464412"; // Replace this with your actual API Key
    const url = `https://api.openweathermap.org/data/2.5/weather?lat=14.5995&lon=120.9842&appid=${apiKey}&units=metric`;

    try {
        const response = await fetch(url);

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json();

        // Update Weather Card
        document.getElementById("temp").innerHTML = `+${Math.round(data.main.temp)}°C`;
        document.getElementById("condition").innerHTML = data.weather[0].description;
        document.getElementById("pressure").innerHTML = `Pressure: ${(data.main.pressure * 0.75006).toFixed(2)} mmHg`; // Convert hPa to mmHg
        document.getElementById("humidity").innerHTML = `Humidity: ${data.main.humidity}%`;
        document.getElementById("wind").innerHTML = `Wind: ${data.wind.speed} m/s`;

        // Simulated Forecast (Morning, Day, Evening, Night)
        document.getElementById("morning").innerHTML = `☁️ +${Math.round(data.main.temp - 2)}°C`;
        document.getElementById("day").innerHTML = `☀️ +${Math.round(data.main.temp + 1)}°C`;
        document.getElementById("evening").innerHTML = `🌬️ +${Math.round(data.main.temp - 1)}°C`;
        document.getElementById("night").innerHTML = `🌙 +${Math.round(data.main.temp - 3)}°C`;
    } catch (error) {
        console.error("Error fetching weather data:", error);
        document.getElementById("temp").innerHTML = "API Key Error";
        document.getElementById("condition").innerHTML = "Check API Key & Network.";
    }
}

// Fetch weather on page load
fetchWeather();

// Auto-update every 10 minutes
setInterval(fetchWeather, 600000);