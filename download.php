<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ottieni l'URL del video da $_POST
    $youtube_url = $_POST['youtube_url'];

    // Directory per salvare i file temporanei
    $temp_dir = "/path/to/temp/dir/";

    // Funzione per scaricare e convertire il video in MP3
    function downloadAndConvertToMP3($url, $temp_dir) {
        // Genera un nome univoco per il file temporaneo
        $temp_file = $temp_dir . uniqid() . ".mp4";

        // Esegui il download del video usando youtube-dl
        exec("youtube-dl --extract-audio --audio-format mp3 -o {$temp_file} {$url}");

        // Nome del file MP3 di output
        $mp3_file = str_replace(".mp4", ".mp3", $temp_file);

        // Esegui la conversione in MP3 usando FFmpeg
        exec("ffmpeg -i {$temp_file} {$mp3_file}");

        // Elimina il file temporaneo MP4
        unlink($temp_file);

        return $mp3_file;
    }

    // Scarica e converte il video in MP3
    $mp3_file = downloadAndConvertToMP3($youtube_url, $temp_dir);

    // Invia il file MP3 all'utente per il download
    header('Content-Type: audio/mpeg');
    header("Content-Disposition: attachment; filename=\"audio.mp3\"");
    readfile($mp3_file);

    // Elimina il file MP3 temporaneo
    unlink($mp3_file);
}
?>
