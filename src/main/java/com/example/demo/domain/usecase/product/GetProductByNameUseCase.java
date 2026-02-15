package com.example.demo.domain.usecase.product;

import java.util.List;
import com.example.demo.domain.entities.product.IProductRepository;
import com.example.demo.domain.entities.product.Product;

public class GetProductByNameUseCase {
    private final IProductRepository productRepository;

    public GetProductByNameUseCase(IProductRepository productRepository) {
        this.productRepository = productRepository;
    }

    public List<Product> execute(String name) {
        return productRepository.findByName(name);
    }
}
