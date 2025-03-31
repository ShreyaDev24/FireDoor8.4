<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
</head>

<body>
<h2>Welcome to the site</h2>
<br/>
<p>Hello {{ $usermname }}, You are assigned for this {{ $projectName }} project. Your scheduled timing is {{ date('d-m-Y h:i a', strtotime($fromTime)) }} to {{ date('d-m-Y H:i a', strtotime($toTime)) }}.</p></br></br><p>Thank You</p>
</body>

</html>
