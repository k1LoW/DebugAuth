<table>
    <tr>
        <th>
            <?php echo __(Inflector::humanize($displayField)); ?>
        </th>       
        <th>
            <?php echo __d('DebugAuth', 'Login ID'); ?>
        </th>
        <th>
            <?php echo __d('DebugAuth', 'Actions'); ?>
        </th>
    </tr>    
    <?php foreach($users as $user): ?>
    <tr>
        <td>
            <?php echo $user[$modelName][$displayField]; ?>
        </td>
        <td>
            <?php echo $user[$modelName][$usernameField]; ?>
        </td>
        <td>
            <?php echo $this->Form->postLink(__d('DebugAuth', 'Login'), array('action' => 'login', $user[$modelName][$primaryKey]), array('class' => 'button')); ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php echo $this->Paginator->numbers(); ?>