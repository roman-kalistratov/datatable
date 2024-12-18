<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multi-Language Auto Translation</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Auto Translation</h1>
    <label for="hebrew">Иврит:</label>
    <input type="text" id="hebrew" placeholder="Введите текст на иврите">
    <br><br>
    <label for="english">Английский:</label>
    <input type="text" id="english" placeholder="Введите текст на английском">
    <br><br>
    <label for="russian">Русский:</label>
    <input type="text" id="russian" placeholder="Введите текст на русском">
    <br><br>
    <script src="script.js"></script>
</body>
</html>

$(document).ready(function () {
    const apiKey = "ВАШ_API_КЛЮЧ"; // Вставьте ваш ключ OpenAI API
    const endpoint = "https://api.openai.com/v1/chat/completions";

    // Общая функция для перевода
    function translate(inputLang, inputText, targetLangs) {
        if (inputText.trim() === "") return;

        const prompt = `
            Translate the following text from ${inputLang} into ${targetLangs.join(" and ")}:
            ${inputLang}: ${inputText}
            ${targetLangs.map(lang => `${lang}:`).join("\n")}
        `;

        $.ajax({
            url: endpoint,
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${apiKey}`
            },
            data: JSON.stringify({
                model: "gpt-4",
                messages: [{ role: "user", content: prompt }],
                max_tokens: 200,
                temperature: 0.7
            }),
            success: function (response) {
                const result = response.choices[0].message.content;

                // Заполняем соответствующие инпуты
                targetLangs.forEach(lang => {
                    const regex = new RegExp(`${lang}:\\s*(.*)`);
                    const match = result.match(regex);
                    const translatedText = match ? match[1] : "Error in translation";

                    if (lang === "English") $("#english").val(translatedText);
                    if (lang === "Russian") $("#russian").val(translatedText);
                    if (lang === "Hebrew") $("#hebrew").val(translatedText);
                });
            },
            error: function (err) {
                console.error("Error: ", err);
                alert("Ошибка перевода. Проверьте консоль для подробностей.");
            }
        });
    }

    // Обработчики для каждого инпута
    $("#hebrew").on("input", function () {
        const hebrewText = $(this).val();
        translate("Hebrew", hebrewText, ["English", "Russian"]);
    });

    $("#english").on("input", function () {
        const englishText = $(this).val();
        translate("English", englishText, ["Hebrew", "Russian"]);
    });

    $("#russian").on("input", function () {
        const russianText = $(this).val();
        translate("Russian", russianText, ["Hebrew", "English"]);
    });
});


 
 
