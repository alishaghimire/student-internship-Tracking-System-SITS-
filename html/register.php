<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../css/proj/register.css">
</head>
<body>
    <div class="register">
        <form action="">
            <h1>Registration</h1>
            
            <div class="inputbox">
                <label for="name" class="dist">Name</label><br>
                <input type="text" name="name">
            </div>

            <div class="inputbox">
                <label for="email" class="dist">Email</label><br>
                <input type="email" name="email">
            </div>

            <div class="inputbox" id="member">
                <label for="people" class="dist">Select</label>
                <select name="people" id="people">
                    <option value="">Select</option>
                    <optgroup label="Individual">
                        <option>Department</option>
                        <option>Company</option>
                        <option>Student</option>
                    </optgroup>
                </select>
            </div> 

            <div class="inputbox">
                <label for="Cpassword" class="dist">Create Password</label><br>
                <input type="password" name="Cpassword">
            </div>

            <div class="inputbox">
                <label for="password" class="dist">Confirm Password</label><br>
                <input type="password" name="password">
            </div>
        
            <div class="submit">
                <input type="submit" value="Register">
            </div>
        </form>
    </div>
</body>
</html>
