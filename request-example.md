## Example of Query Request 
{
    "query": "query {\ntodos { title }\n}"
}

## Example of Mutation Request 
```
{
    "query": "mutation {  saveTodo(input: { title: \"hello\",body: \"body\"} ){title \n body}}"
}
```

## Apache Benchmark (AB) Test 
```
ab -p post_loc.txt -T application/json -H 'Authorization: Token abcd1234' -c 10 -n 2000 http://example.com/api/v1/locations/
```

