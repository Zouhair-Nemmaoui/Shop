import './Products.css'
import { useState, useEffect, Fragment} from 'react';
import useFetch from '../hooks/useFetch'
import Checkbox from './Checkbox';

export default function Categories() {
    const [categories,setCategories] = useState([])
    const {data, loading, error} = useFetch("/categories?populate=*")

  
    useEffect (()=>{
data && setCategories(data)
console.log(categories)

  },[data])
    return (
        <div className='categories'>
    
 {loading ? "Loading..." : categories.map(
        category => (
         <Fragment key={category.id}>
             <Checkbox category={category} />
         </Fragment>
        ))}
        </div>
    )
}