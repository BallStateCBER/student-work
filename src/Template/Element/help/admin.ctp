<h1 align='center'>
    <?= $titleForLayout ?>
</h1>
<?= $this->Html->image('staff-help.jpg', [
    'alt' => 'CBER staff sit down for a lunch',
    'class' => 'home-img float-right'
]) ?>
<p>
    Hi, <?= isset($activeUser['name']) ? $activeUser['name'] : 'Employee #' . $activeUser['id'] ?>! Welcome to <i>Why Are We Here?</i>, CBER's Work Tracker.
    We use this site to track projects, assign projects to students & staff, write reports on those projects, and maintain a database of student & staff information.
    Here's a quick guide on how to use this website.
</p>
<h3 align='left'>
    Student user guide
</h3>
<p>
    Before we get started, please visit the <a href="/student-help">student version</a> of the user guide. This will walk you through basic tasks (such as logging in,
    editing account information, accessing the indexes, and manipulating reports). This will also help you to understand what our students experience when they use
    this site.
</p>
<h3 align='left'>
    Site admin abilities & responsibilities
</h3>
<p>
    As an admin, you have the ability to do anything you want on this site, except delete the site itself. You can add, edit, and delete anything:
    projects, reports, users & user information, and funding information. You have the ability to sabotage us and delete all of the information on this site if you
    feel like it. All of our databases are backed up on a monthly basis, so doing that would kind of just be an annoying waste of time, but as an admin you still have
    that ability.
</p>
<p>
    Students are much more limited in their ability to use the site, which means they depend on site admins to give them opportunities to use the site. For your students,
    you must create accounts for them using their <u>@bsu.edu</u> email, and create projects for them to generate reports from. It is also your responsibility to add
    them as contributors.
</p>
<h3 align="left">
    Adding, editing, and deleting users
</h3>
<a href="/img/register.png">
    <?= $this->Html->image('register.png', [
        'alt' => 'Registering users',
        'class' => 'home-img screenshot tutorial float-left'
    ]) ?>
</a>
<p>
    As an admin, you have the responsibility to add other CBER employees to the site. This includes making accounts for other admins. At the navbar at the top of the
    screen, under the <u>Students & Staff</u> menu, is <a href="/register">Add a User</a>. This link takes you to the screen where you can register other accounts. This
    work tracker only uses <u>@bsu.edu</u> email addresses, and uses BSU ID numbers.
</p>
<p>
    By visiting the <a href="/employees">Students & Staff index</a> under the Students & Staff navbar menu, you have the ability to edit every single account on the
    student work tracker. Clicking <u>edit</u> for a user will bring you to a similar form as for editing your own account information. Individual users should edit
    their own account information. It is unlikely that you will have to, for whatever reason. You can also reset any user's password or delete any user account. <u>
        For the time being, please don't delete other people's accounts. If someone leaves CBER, their admin privileges are stripped and their accounts effectively
        become disabled. We may just take away the ability to delete user accounts altogether, we may put in some safety net features to prevent deleting accounts
        from breaking the reports or projects sections, either way we haven't decided what to do about that yet so please don't delete any accounts.
    </u>
</p>
<h3 align="left">
    Funds
</h3>
<p>
    All admins have the ability and responsibility to keep track of funds. The funds index has an actions column which allows admins to edit and delete fund
    information, and funds information can be added by clicking <a href="/funds/add">New Funding Source</a> under the header of the funds index. All funding information
    is viewable from the funds index. No student can access this section of the site.
</p>
<h3 align="left">
    Adding, editing, and deleting projects
</h3>
<a href="/img/add-a-project.png">
    <?= $this->Html->image('add-a-project.png', [
        'alt' => 'Adding a project',
        'class' => 'home-img screenshot tutorial float-right'
    ]) ?>
</a>
<p>
    It's also your responsibility as an admin to add projects to the work tracker, and add users to them. By clicking <a href="/projects/add">Add a Projects</a> under
    the <u>Projects</u> dropdown menu, you will be directed to a page where you can describe the project you're adding. After filling the form out, you will be
    directed to the projects index. In the actions column of the index, you'll see links to view, edit, and delete projects. You won't have the ability to delete
    a project if there are reports associated with it. A new project won't have reports associated with it yet, though, so you can still delete it if you need to.
</p>
<a href="/img/new-project.png">
    <?= $this->Html->image('new-project.png', [
        'alt' => 'What a new project looks like in the index',
        'class' => 'home-img screenshot tutorial float-left'
    ]) ?>
</a>
<p>
    To add contributors to a project, go to that project's edit page and click the <u>(+)</u> sign next to the Edit Contributors header. This will allow you to select
    the user's name and input their role in the project. If you need to add another user, click <U>Add Another?</U> in the third column of the contributor section.
    If you need to change a user's role, select the <u>Delete This Role?</u> checkbox and add them as a new contributor. Please refer to the animated gif below
    as an example.
</p>
<?= $this->Html->image('adding-contributors.gif', [
    'class' => 'animation tutorial float-right'
]) ?>
<p>
    Remember: only site admins can change details about projects. Students may look at them, but nothing more. Project moderation is an admin responsibility and can help
    students or new staff better understand our workflow here at CBER.
</p>
<h3 align="left">
    Adding, editing, and deleting reports
</h3>
<p>
    This works just the same for admins as it does for students, the only difference being that admins have the ability to edit and delete any report they wish,
    regardless of whether or not they contributed.
</p>
<p>
    Oh boy, that's a lot to take in. By using this work tracker, we will effectively be creating a log of all the
    things we do here at CBER that make it such a wonderful place to work, and an invaluable asset to our region. Thank you for sitting through this user guide!
</p>