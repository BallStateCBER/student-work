<h1 align='center'>
    <?= $titleForLayout ?>
</h1>
<?= $this->Html->image('student-help.jpg', [
    'alt' => 'A student graduates from Ball State',
    'class' => 'home-img float-right'
]) ?>
<p>
    Hi, <?= isset($activeUser['name']) ? $activeUser['name'] : 'Student #' . $activeUser['id'] ?>! Welcome to <em>Why Are We Here?</em>, CBER's Work Tracker.
    We use this site to track projects, assign projects to students & staff, write reports on those projects, and maintain a database of student & staff information.
    Here's a quick guide on how to use this website. All example images can be enlarged, clicked, and enlarged further.
</p>
<h3 align='left'>
    Logging in
</h3>
<p>
    A site administrator will need to create you an account. After that, they'll give you the credentials you need to log in.
    To get started, click the <a href="/login">Log in</a> link above, at the upper-left hand corner of the screen. Please enter your <u>@bsu.edu</u> email address,
    and the password given to you when the account was created for you.
</p>
<a href="/img/account.png">
    <?= $this->Html->image('account.png', [
        'alt' => 'Filling out your account information',
        'class' => 'home-img screenshot tutorial float-left'
    ]) ?>
</a>
<h3 align='left'>
    Account information
</h3>
<p>
    Please fill out your account information after your account has been created. You can do this by clicking <a href="/account">Edit Your Account</a> at the top
    of the screen. You may add as much or as little information as you wish, including any degrees or awards you've earned in your academic career.
    <strong>It is most helpful if you provide as much information about yourself as possible!</strong> We collect this information so that we can use the site for a variety of purposes:
    providing job references for new or soon-to-be grads, writing bios for events or other websites, and just getting to know each other.
</p>
<p>
    Your account information page is also where, using small links at the bottom-right hand corner of the screen, you can reset or delete your password. <u>
        For the time being, please don't delete your account. Your account will be disabled automatically if you leave CBER, outside of logging in and seeing indexes.
    </u>
</p>
<h3 align='left'>
    Projects & reports index
</h3>
<p>
    By clicking <a href="/projects">Projects</a> at the top of the screen, you may view ongoing or completed projects we're working on here at CBER. Each row in the
    projects index has a link for you to view each individual project.
</p>
<p>
    Clicking <a href="/reports">Reports Index</a> under the <u>Student Reports</u> menu above will take you to our reports index. These reports can be indexed by project
    name, whether they're ongoing or completed projects, and the students & supervisors working on them. Using the actions column in the index, you'll be able to view all
    the reports, and edit or delete ones you own.
</p>
<h3 align='left'>
    Adding, editing, and deleting reports
</h3>
<p>
    To add a report, click <a href="/reports/add">Add a Report</a> under the <u>Student Reports</u> menu at the top of the screen. You will only be able to add reports
    for yourself, and for projects that you have been added to as a contributor.
</p>
<p>
    <em>Hey, wait, why are there no project names? All I see is "No Projects Added"!</em>
    <br /><em>I have a list of old projects I've done, but not my current projects!</em>
    <br />What this means is that you have not been added as a contributor to any projects, or to the project you need to write a report on.
    Please let your supervisor know. It is the supervisor's responsibility to assign your projects.
</p>
<a href="/img/add-a-report.png">
    <?= $this->Html->image('add-a-report.png', [
        'alt' => 'Adding a report',
        'class' => 'home-img screenshot tutorial float-right'
    ]) ?>
</a>
<p>
    Once they've added you to a project, you'll be able to write your reports on it. You have the ability to edit and delete any reports that you're a contributor
    to.
</p>
<h3 align='left'>
    Students & Staff index
</h3>
<p>
    Next to the student reports menu, at the top of the screen, is the <a href="/employees">Students & Staff</a> link. This will take you to our index of current &
    former CBER employees. Using this index, we can learn about each other, serve as effective job references for each other, and remember each other's birthdays.
</p>
<p>
    Thank you for using this site! We are the best place to work for at Ball State and this site catalogs why.
    I hope we here at Ball State's Center for Business and Economic Research can make your employment experience a productive, fun,
    and fulfilling one.
</p>