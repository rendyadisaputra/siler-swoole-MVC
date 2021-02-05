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
ab -p post-example.txt -T application/json -H 'Authorization: Token abcd1234' -c 10 -n 2000 http://192.168.56.66:8000/graphql
```

