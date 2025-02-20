<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    {{-- <meta name="viewport" content="width=device-width, initial-scale=1"> --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title inertia>{{ config('app.name', 'Laravel') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href = "https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel = "stylesheet">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel = "stylesheet">
    <!-- Scripts -->
    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead

    <style>
        .custom-button {
            background-color: #63b5f6;
            /* Light blue background color */
            color: white;
            /* Text color */
            font-weight: bold;
            /* Bold text */
            font-size: 16px;
            /* Font size */
            border: none;
            /* Remove border */
            border-radius: 50px;
            /* Rounded corners */
            padding: 10px 20px;
            /* Adjust padding */
            box-shadow: 0px 4px 6px rgba(56, 79, 80, 0.4);
            /* Add shadow */
            cursor: pointer;
            /* Pointer on hover */
            transition: transform 0.2s, box-shadow 0.2s;
            /* Smooth animations */
        }

        .custom-button:hover {
            transform: scale(1.05);
            /* Slight zoom on hover */
            box-shadow: 0px 6px 8px rgba(0, 0, 0, 0.6);
            /* Stronger shadow on hover */
        }

        .custom-button:focus {
            outline: none;
            /* Remove focus outline */
        }

        .countdown-timer {
            gap: 15px;
            /* Adjust spacing between time-boxes */
        }

        .time-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 70px;
            height: 70px;
            background-color: #fff;
            /* White background */
            border: 2px solid #e0e0e0;
            /* Light gray border */
            border-radius: 50%;
            /* Make it circular */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            /* Add subtle shadow */
            font-family: Arial, sans-serif;
            /* Set font */
        }

        .time-number {
            font-size: 24px;
            /* Large number font size */
            font-weight: bold;
            /* Bold number */
            color: #63b5f6;
            /* Light blue color */
        }

        .time-label {
            font-size: 12px;
            /* Smaller font size for labels */
            color: #6c757d;
            /* Subtle gray color for labels */
            text-transform: lowercase;
            /* Lowercase text */
            margin-top: -5px;
            /* Adjust spacing */
        }

        /* Progress Bar Container */
        .progress-bar {
            width: 100%;
            /* Full width */
            height: 20px;
            /* Height of the progress bar */
            background-color: #e0e0e0;
            /* Light gray background */
            border-radius: 10px;
            /* Rounded corners */
            overflow: hidden;
            /* Prevent overflow of child elements */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            /* Subtle shadow */
            position: relative;
            /* For positioning the text */
        }

        /* Progress Fill */
        .progress-fill {
            height: 100%;
            /* Fill the height of the container */
            background-color: #63b5f6;
            /* Blue fill color */
            width: 0;
            /* Default width, will be set dynamically */
            border-radius: 10px 0 0 10px;
            /* Rounded left side */
            transition: width 0.5s ease-in-out;
            /* Smooth transition */
            display: flex;
            /* Center the text inside the bar */
            align-items: center;
            /* Vertically center the text */
            justify-content: center;
            /* Horizontally center the text */
            color: white;
            /* Text color */
            font-weight: bold;
            /* Bold text */
            position: relative;
            /* For proper text alignment */
        }

        /* Progress Text */
        .progress-text {
            font-size: 0.6rem;
            /* Text size */
            z-index: 1;
            /* Ensure text is above the fill */
            color: white;
            /* Text color inside the bar */
        }
    </style>
</head>

<body class="font-sans antialiased">
    @inertia

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"></script>


</body>

</html>
