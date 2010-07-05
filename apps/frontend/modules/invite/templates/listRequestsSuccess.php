<h1>Invite requests</h1>

<table id="waitinglist">
  <thead>
    <tr>
      <td>Email</td>
      <td>Firstname</td>
      <td>Lastname</td>
      <td>URL</td>
      <td></td>
    </tr>
  </thead>
<?php foreach ($requests as $request): ?>
  <tr>
    <td><?php echo $request['email']; ?></td>
    <td><?php echo $request['firstname']; ?></td>
    <td><?php echo $request['lastname']; ?></td>
    <td><?php echo $request['url']; ?></td>
    <td><?php echo link_to('Send Invite', 'invite/send?id=' . $request['id']); ?></td>
  </tr>
<?php endforeach; ?>
</table>