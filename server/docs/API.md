# API Document

## task management

1. add a task
```json
{
    'action': 'add',
    'target': [
        'node': 'hash-of-node',
        'project': 'hash-of-project'
    ],
    'data': [
        'app': [
            'name': 'name of task',
            'id': 'hash-of-task',
            'command': 'task-command'
            'param': 'param of task',
        ],
        'schedule': [
            'interval': 3600,
            'timer': '%Y-%m-%d 05:10:00, %Y-%m-%d %H:%M:00, %Y-%m-%d %H:%M:40, %Y-%m-%d %H:%M:20, %Y-%m-%d %H:57:15'
        ],
        'error': [
            'min_time': 1,
            'max_time': 300,
            'timeout': 3600,
            'retry': 3,
            'retry_interval': 2,
            'error_code': {2, 3, 4}
        ],
        'notify': [
            'enabled': true,
            'email': 'cron.run@ipengxh.com,cron.run@pt-software.com',
            'success': false,
            'error': true
        ]
    ]
}
```

2. update a task
```json
{
    'action': 'update',
    'target': [
        'node': 'hash-of-node',
        'project': 'hash-of-project',
        'task': 'hash-of-task'
    ],
    'data': [
        'app': [
            'name': 'name of task',
            'id': 'hash-of-task',
            'command': 'task-command'
            'param': 'param of task',
        ],
        'schedule': [
            'interval': 3600,
            'timer': '%Y-%m-%d 05:10:00, %Y-%m-%d %H:%M:00, %Y-%m-%d %H:%M:40, %Y-%m-%d %H:%M:20, %Y-%m-%d %H:57:15'
        ],
        'error': [
            'min_time': 1,
            'max_time': 300,
            'timeout': 3600,
            'retry': 3,
            'retry_interval': 2,
            'error_code': {2, 3, 4}
        ],
        'notify': [
            'enabled': true,
            'email': 'cron.run@ipengxh.com,cron.run@pt-software.com',
            'success': false,
            'error': true
        ]
    ]
}
```

3. delete a task
```json
{
    'action': 'delete',
    'target': [
        'node': 'hash-of-node',
        'project': 'hash-of-project',
        'task': 'hash-of-task'
    ]
}
```

4. get current status of a task
```json
{
    'action': 'status',
    'target': [
        'node': 'hash-of-node',
        'project': 'hash-of-project',
        'task': 'hash-of-task'
    ]
}
```

5. boot a task manually
```json
{
    'action': 'boot',
    'target': [
        'node': 'hash-of-node',
        'project': 'hash-of-project',
        'task': 'hash-of-task'
    ]
}
```

6. boot a task manually with params
```json
{
    'action': 'boot',
    'target': [
        'node': 'hash-of-node',
        'project': 'hash-of-project',
        'task': 'hash-of-task',
        'param': 'param of command'
    ]
}
```
