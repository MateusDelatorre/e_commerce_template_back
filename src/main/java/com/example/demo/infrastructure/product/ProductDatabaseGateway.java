package com.example.demo.infrastructure.product;

import com.example.demo.data.repositories.ProductRepository;
import com.example.demo.data.schema.ProductSchema;
import com.example.demo.domain.entities.product.IProductRepository;
import com.example.demo.domain.entities.product.Product;

import java.math.BigDecimal;
import java.util.List;

public class ProductDatabaseGateway implements IProductRepository {
    ProductRepository productRepository;

    public ProductDatabaseGateway(ProductRepository productRepository) {
        this.productRepository = productRepository;
    }


    @Override
    public Product save(Product product) {
        ProductSchema productSchema = new ProductSchema(product);
        return productRepository.save(productSchema).toProduct();
    }

    @Override
    public Product findById(Long id) {
        return productRepository.findById(id).orElse(null).toProduct();
    }

    @Override
    public List<Product> findByCategory(String category) {
        if (category == null || category.isEmpty()) {
            return List.of();
        }
        return productRepository.findByCategoryName(category)
                .stream()
                .map(ProductSchema::toProduct)
                .toList();
    }

    @Override
    public List<Product> findByName(String name) {
        if (name == null || name.isEmpty()) {
            return List.of();
        }
        return productRepository.findByNameContaining(name)
                .stream()
                .map(ProductSchema::toProduct)
                .toList();
    }

    @Override
    public List<Product> findByPriceRange(BigDecimal minPrice, BigDecimal maxPrice) {
        if (minPrice == null || maxPrice == null) {
            return List.of();
        }
        return productRepository.findByPriceBetween(minPrice, maxPrice)
                .stream()
                .map(ProductSchema::toProduct)
                .toList();
    }

    @Override
    public void deleteById(Long id) {
        if (id == null) {
            return;
        }
        if (!productRepository.existsById(id)) {
            return;
        }
        productRepository.deleteById(id);
        productRepository.existsById(id);
    }
}
