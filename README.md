
# Job Queue Laravel

## Requirements

1) All laravel requirements. https://laravel.com/docs/7.x/installation#installation
2) MySQL or another relational database engine.

## Installing 
1) Clone project in a www folder of your web server
2) `composer install` to install all Laravel packages & dependencies
3) Copy the `.env.example` as `.env` and set the username/passwords and configs. 
4) Run the `schema.sql` in your database to get all tables. Database name: `jobs_queue`


## Routes 
- Please import the `POSTMAN` collection to check all routes. File: `postman_collection_jobs_queue.json`

- Submitter
    - url: `api/submitter` | (method: get)
        <br> Show all submitters 
    - url: `api/submitter/{submitter_id}` | (method: get)
        <br> Show the submitter 
    - url: `api/submitter` | (method: post)
        <br> Create a new submitter 
    - url: `api/submitter/{submitter_id}` | (method: patch)
        <br> Update a submitter 
    - url: `api/submitter/{submitter_id}` | (method: delete)
        <br> Remove a submitter that has no links to other's tables.
        
- Processor
    - url: `api/processor` | (method: get)
        <br> Show all processors 
    - url: `api/processor/{processor_id}` | (method: get)
        <br> Show the processor
    - url: `api/processor` | (method: post)
        <br> Create a new processor 
    - url: `api/processor/{processor_id}` | (method: patch)
        <br> Update a processor 
    - url: `api/processor/{processor_id}` | (method: delete)
        <br> Remove a processor that has no links to other's tables.
    - url: `api/processor/nextJob/{processor_id}` | (method: get)
        <br> Assign the next job on the queue for the processor
    - url: `api/processor/finishJob/{processor_id}` | (method: get)
        <br> Finish the current assigned job for the processor
        
- Job
    - url: `api/job` | (method: get)
        <br> Show all jobs 
    - url: `api/processor/{job_id}` | (method: get)
        <br> Show the job
    - url: `api/job` | (method: post)
        <br> Create a new job 
    - url: `api/job/{job_id}` | (method: patch)
        <br> Update a job that hasn't started  
    - url: `api/job/{job_id}` | (method: delete)
        <br> Remove a job that isn't processing (can delete finished and recently created)
    - url: `api/report/job/` | (method: get)
        <br> Show all details of all jobs (all the data + average time, status).
    - url: `api/report/job/{job_id}` | (method: get)
        <br> Show all details of the job  (all the data + average time, status).    
 
 
 
 
## Notes:
- This project doesn't have any auth by now (oauth, jwt or others)

