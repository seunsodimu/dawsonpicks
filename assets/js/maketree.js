function getSortedJson(arr, parent) {
  var out = [];
  for(var i in arr) {
      if(arr[i].parent == parent) {
          var children = getSortedJson(arr, arr[i].id);

          if(children.length) {
              arr[i].children = children;
          }
          out.push(arr[i]);
      }
  }
  return out;
}
