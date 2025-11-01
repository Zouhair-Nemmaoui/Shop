import './Products.css'
import { useState, useEffect, useContext } from 'react';
import useFetch from '../hooks/useFetch'
import StoreContext from '../hooks/storeContext';
import { addToCart } from '../redux/cartReducer';
import { useDispatch } from 'react-redux';
export default function Products() {
    const [products,setProducts] = useState([])
  
  const {filter} = useContext(StoreContext)

    const {data, loading, error} = useFetch(filter)
  useEffect (()=>{
data && setProducts(data)
  },[data])

  const dispatch = useDispatch()


    return (
        <div className='products'>
     {loading ? "Loading..." : products.map(
        product => (
            <div className='product' key={product.id}>
              <h2 className='title'> {product.attributes.title} </h2>  
              <div className='price'> {product.attributes.price} </div> 
              <img className='img' src={`${import.meta.env.VITE_APP_URL}${product.attributes.image.data.attributes.url}`}/>
              <div className='desc'> {product.attributes.description} </div>  
              <button className='pro-button' onClick={
                ()=>dispatch(addToCart({
                  id : product.id,
                  title : product.attributes.title,
                  description : product.attributes.description,
                  price: product.attributes.price,
                  image: product.attributes.image.data.attributes.url
                }))}>add to cart</button>
            </div>
        ))}
        </div>
    )
}