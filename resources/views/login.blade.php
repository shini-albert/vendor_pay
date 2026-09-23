<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment-login</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }

    body {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background-color: #f8fafc;
      color: #0f172a;
    }

    form {
      background: #ffffff;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      border: 1px solid #e2e8f0;
      width: 100%;
      max-width: 320px;
    }

    h2 {
      margin-bottom: 1.25rem;
      font-size: 1.35rem;
      font-weight: 600;
      text-align: center;
    }

    label {
      display: block;
      margin-bottom: 0.35rem;
      font-size: 0.85rem;
      font-weight: 500;
      color: #475569;
    }

    input {
      width: 100%;
      padding: 0.55rem 0.75rem;
      margin-bottom: 1rem;
      border: 1px solid #cbd5e1;
      border-radius: 6px;
      font-size: 0.9rem;
      outline: none;
      transition: border-color 0.2s, box-shadow 0.2s;
    }

    input:focus {
      border-color: #2563eb;
      box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    button {
      width: 100%;
      padding: 0.6rem;
      margin-top: 0.25rem;
      background-color: #0f172a;
      color: #ffffff;
      border: none;
      border-radius: 6px;
      font-size: 0.9rem;
      font-weight: 500;
      cursor: pointer;
      transition: background-color 0.2s;
    }

    button:hover {
      background-color: #1e293b;
    }
  </style>
</head>
<body>

  <form action="#" method="POST">
    <h2>Login</h2>
    
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Login</button>
  </form>

</body>
</html>